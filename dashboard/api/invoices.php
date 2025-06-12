<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$user_id = $_SESSION['user_id'];

switch ($method) {
    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'download') {
            handleDownloadInvoice();
        } else {
            handleGetInvoices();
        }
        break;
    case 'POST':
        handleCreateInvoice();
        break;
    case 'PUT':
        handleUpdateInvoice();
        break;
    case 'DELETE':
        handleDeleteInvoice();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetInvoices() {
    global $pdo, $user_id;
    
    try {
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("
                SELECT i.*, p.first_name, p.last_name, 
                       CONCAT(p.first_name, ' ', p.last_name) as patient_name
                FROM invoices i
                JOIN patients p ON i.patient_id = p.id
                WHERE i.id = ? AND i.user_id = ?
            ");
            $stmt->execute([$_GET['id'], $user_id]);
            $invoice = $stmt->fetch();
            
            if ($invoice) {
                // Get invoice items
                $stmt = $pdo->prepare("
                    SELECT ii.*, s.name as service_name
                    FROM invoice_items ii
                    LEFT JOIN services s ON ii.service_id = s.id
                    WHERE ii.invoice_id = ?
                ");
                $stmt->execute([$invoice['id']]);
                $invoice['items'] = $stmt->fetchAll();
                
                echo json_encode($invoice);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Invoice not found']);
            }
        } else {
            $stmt = $pdo->prepare("
                SELECT i.*, p.first_name, p.last_name
                FROM invoices i
                JOIN patients p ON i.patient_id = p.id
                WHERE i.user_id = ?
                ORDER BY i.created_at DESC
            ");
            $stmt->execute([$user_id]);
            $invoices = $stmt->fetchAll();
            echo json_encode($invoices);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleCreateInvoice() {
    global $pdo, $user_id;
    
    try {
        $required_fields = ['patient_id', 'invoice_number'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Missing required field: $field"]);
                return;
            }
        }
        
        // Calculate totals
        $subtotal = 0;
        $items = [];
        
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            foreach ($_POST['items'] as $item) {
                if (!empty($item['description']) && !empty($item['unit_price'])) {
                    $quantity = intval($item['quantity'] ?? 1);
                    $unit_price = floatval($item['unit_price']);
                    $total_price = $quantity * $unit_price;
                    $subtotal += $total_price;
                    
                    $items[] = [
                        'service_id' => $item['service_id'] ?? null,
                        'description' => $item['description'],
                        'quantity' => $quantity,
                        'unit_price' => $unit_price,
                        'total_price' => $total_price
                    ];
                }
            }
        }
        
        $tax_amount = floatval($_POST['tax_amount'] ?? 0);
        $discount_amount = floatval($_POST['discount_amount'] ?? 0);
        $total_amount = $subtotal + $tax_amount - $discount_amount;
        
        // Insert invoice
        $stmt = $pdo->prepare("
            INSERT INTO invoices (
                user_id, patient_id, invoice_number, subtotal, tax_amount, 
                discount_amount, total_amount, status, due_date, payment_method, notes, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $user_id,
            $_POST['patient_id'],
            $_POST['invoice_number'],
            $subtotal,
            $tax_amount,
            $discount_amount,
            $total_amount,
            $_POST['status'] ?? 'draft',
            $_POST['due_date'] ?? null,
            $_POST['payment_method'] ?? null,
            $_POST['notes'] ?? null
        ]);
        
        $invoice_id = $pdo->lastInsertId();
        
        // Insert invoice items
        foreach ($items as $item) {
            $stmt = $pdo->prepare("
                INSERT INTO invoice_items (invoice_id, service_id, description, quantity, unit_price, total_price)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $invoice_id,
                $item['service_id'],
                $item['description'],
                $item['quantity'],
                $item['unit_price'],
                $item['total_price']
            ]);
        }
        
        // Log activity
        $stmt = $pdo->prepare("INSERT INTO activities (user_id, type, title, description, created_at) VALUES (?, 'payment_received', 'New invoice created', ?, NOW())");
        $stmt->execute([$user_id, 'Invoice ' . $_POST['invoice_number'] . ' created']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Invoice created successfully',
            'invoice_id' => $invoice_id
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleUpdateInvoice() {
    global $pdo, $user_id;
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invoice ID is required']);
            return;
        }
        
        // Verify invoice belongs to clinic
        $stmt = $pdo->prepare("SELECT id FROM invoices WHERE id = ? AND user_id = ?");
        $stmt->execute([$input['id'], $user_id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Invoice not found']);
            return;
        }
        
        $fields = [];
        $params = [];
        
        // Format paid_date if provided
        if (isset($input['paid_date'])) {
            try {
                $date = new DateTime($input['paid_date']);
                $input['paid_date'] = $date->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                $input['paid_date'] = date('Y-m-d H:i:s'); // Use current date if parsing fails
            }
        }
        
        // Allow updating more fields
        $allowed_fields = ['status', 'paid_date', 'payment_method', 'notes', 'tax_amount', 'discount_amount', 'due_date'];
        foreach ($allowed_fields as $field) {
            if (isset($input[$field])) {
                $fields[] = "$field = ?";
                $params[] = $input[$field];
            }
        }
        
        // Set paid_date automatically when marking as paid
        if (isset($input['status']) && $input['status'] === 'paid' && !isset($input['paid_date'])) {
            $fields[] = "paid_date = NOW()";
        }
        
        if (empty($fields)) {
            http_response_code(400);
            echo json_encode(['error' => 'No fields to update']);
            return;
        }
        
        $fields[] = "updated_at = NOW()";
        $params[] = $input['id'];
        
        $query = "UPDATE invoices SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        
        // Add activity log for payment
        if (isset($input['status']) && $input['status'] === 'paid') {
            $stmt = $pdo->prepare("INSERT INTO activities (user_id, type, title, description, created_at) VALUES (?, 'payment_received', 'Payment received', ?, NOW())");
            $stmt->execute([$user_id, 'Payment received for invoice #' . $input['id']]);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Invoice updated successfully',
            'status' => $input['status'] ?? null
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleDeleteInvoice() {
    global $pdo, $user_id;
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invoice ID is required']);
            return;
        }
        
        // Verify invoice belongs to clinic
        $stmt = $pdo->prepare("SELECT id FROM invoices WHERE id = ? AND user_id = ?");
        $stmt->execute([$input['id'], $user_id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Invoice not found']);
            return;
        }
        
        // Delete invoice items first
        $stmt = $pdo->prepare("DELETE FROM invoice_items WHERE invoice_id = ?");
        $stmt->execute([$input['id']]);
        
        // Delete invoice
        $stmt = $pdo->prepare("DELETE FROM invoices WHERE id = ?");
        $stmt->execute([$input['id']]);
        
        echo json_encode(['success' => true, 'message' => 'Invoice deleted successfully']);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleDownloadInvoice() {
    global $pdo, $user_id;
    
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invoice ID is required']);
        return;
    }
    
    try {
        // Get invoice data
        $stmt = $pdo->prepare("
            SELECT i.*, p.first_name, p.last_name, p.email, p.phone, p.address,
                   c.name as clinic_name, c.address as clinic_address, c.phone as clinic_phone
            FROM invoices i
            JOIN patients p ON i.patient_id = p.id
            JOIN clinics c ON i.user_id = c.id
            WHERE i.id = ? AND i.user_id = ?
        ");
        $stmt->execute([$_GET['id'], $user_id]);
        $invoice = $stmt->fetch();
        
        if (!$invoice) {
            http_response_code(404);
            echo json_encode(['error' => 'Invoice not found']);
            return;
        }
        
        // Get invoice items
        $stmt = $pdo->prepare("
            SELECT ii.*, s.name as service_name
            FROM invoice_items ii
            LEFT JOIN services s ON ii.service_id = s.id
            WHERE ii.invoice_id = ?
        ");
        $stmt->execute([$invoice['id']]);
        $items = $stmt->fetchAll();
        
        // Generate PDF
        require_once '../../vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf();
        
        // Add PDF content
        $html = '
        <style>
            .invoice-header { padding: 20px 0; }
            .invoice-details { margin: 20px 0; }
            .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; }
            .total-section { margin-top: 20px; }
        </style>
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>Invoice #: '.$invoice['invoice_number'].'</p>
            <p>Date: '.date('M j, Y', strtotime($invoice['created_at'])).'</p>
        </div>
        
        <div class="invoice-details">
            <div style="float: left; width: 50%;">
                <h3>From:</h3>
                <p>'.$invoice['clinic_name'].'</p>
                <p>'.$invoice['clinic_address'].'</p>
                <p>Phone: '.$invoice['clinic_phone'].'</p>
            </div>
            <div style="float: right; width: 50%;">
                <h3>To:</h3>
                <p>'.$invoice['first_name'].' '.$invoice['last_name'].'</p>
                <p>'.$invoice['address'].'</p>
                <p>Phone: '.$invoice['phone'].'</p>
            </div>
            <div style="clear: both;"></div>
        </div>';
        
        $html .= '
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($items as $item) {
            $html .= '
            <tr>
                <td>'.$item['description'].'</td>
                <td>'.$item['quantity'].'</td>
                <td>$'.number_format($item['unit_price'], 2).'</td>
                <td>$'.number_format($item['total_price'], 2).'</td>
            </tr>';
        }
        
        $html .= '</tbody></table>';
        
        $html .= '
        <div class="total-section">
            <p>Subtotal: $'.number_format($invoice['subtotal'], 2).'</p>
            <p>Tax: $'.number_format($invoice['tax_amount'], 2).'</p>
            <p>Discount: $'.number_format($invoice['discount_amount'], 2).'</p>
            <p><strong>Total: $'.number_format($invoice['total_amount'], 2).'</strong></p>
        </div>';
        
        $mpdf->WriteHTML($html);
        
        // Output PDF
        $mpdf->Output('Invoice-'.$invoice['invoice_number'].'.pdf', 'D');
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
