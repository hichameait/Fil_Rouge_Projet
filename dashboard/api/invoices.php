<?php
require_once __DIR__ . '/vendor/autoload.php';
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

function handleGetInvoices()
{
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
                // Get invoice items (update to use base_services)
                $stmt = $pdo->prepare("
                    SELECT ii.*, bs.name as service_name
                    FROM invoice_items ii
                    LEFT JOIN base_services bs ON ii.service_id = bs.id
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

function handleCreateInvoice()
{
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

        // Ensure 'description' is set for the invoice (required by DB)
        $invoice_description = $_POST['description'] ?? '';
        if (empty($invoice_description) && !empty($items)) {
            // Use first item's description if available
            $invoice_description = $items[0]['description'];
        }
        if (empty($invoice_description)) {
            $invoice_description = 'N/A';
        }

        $tax_amount = floatval($_POST['tax_amount'] ?? 0);
        $discount_amount = floatval($_POST['discount_amount'] ?? 0);
        $total_amount = $subtotal + $tax_amount - $discount_amount;

        // Insert invoice
        $stmt = $pdo->prepare("
            INSERT INTO invoices (
                user_id, patient_id, invoice_number, description, subtotal, tax_amount, 
                discount_amount, total_amount, status, due_date, payment_method, notes, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $user_id,
            $_POST['patient_id'],
            $_POST['invoice_number'],
            $invoice_description,
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

function handleUpdateInvoice()
{
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

function handleDeleteInvoice()
{
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

function handleDownloadInvoice()
{
    global $pdo, $user_id;

    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invoice ID is required']);
        return;
    }

    try {
        // Fetch invoice and items (replace with your actual fetch logic)
        // ...fetch $invoice and $items...

        // Example variables for demonstration (replace with real fetch logic)
        $invoice = [
            'invoice_number' => 'INV-0001',
            'created_at' => date('Y-m-d'),
            'clinic_name' => 'SmileDesk Clinic',
            'clinic_address' => '123 Dental Street',
            'clinic_phone' => '+212-522-123456',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address' => '456 Patient Ave',
            'phone' => '+212-600-000000',
            'subtotal' => 500,
            'tax_amount' => 50,
            'discount_amount' => 0,
            'total_amount' => 550
        ];
        $items = [
            [
                'description' => 'Consultation',
                'quantity' => 1,
                'unit_price' => 500,
                'total_price' => 500
            ]
        ];

        // PDF generation (TCPDF)
        require_once __DIR__ . '/vendor/autoload.php';
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('SmileDesk');
        $pdf->SetAuthor('SmileDesk');
        $pdf->SetTitle('Facture ' . $invoice['invoice_number']);
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        // Simple HTML for invoice
        $html = '
        <style>
            body { font-family: Arial, Helvetica, sans-serif; color: #222; }
            .invoice-header { background: #0284c7; color: #fff; padding: 20px; border-radius: 10px 10px 0 0; }
            .invoice-header h1 { margin: 0 0 8px 0; font-size: 2rem; }
            .invoice-details { margin: 20px 0; }
            .invoice-details div { margin-bottom: 8px; }
            .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; }
            .items-table th { background: #0284c7; color: #fff; }
            .total-section { margin-top: 20px; text-align: right; }
            .total-section p { margin: 0; }
        </style>
        <div class="invoice-header">
            <h1>Facture</h1>
            <div><strong>N°:</strong> ' . htmlspecialchars($invoice['invoice_number']) . '</div>
            <div><strong>Date:</strong> ' . date('d/m/Y', strtotime($invoice['created_at'])) . '</div>
        </div>
        <div class="invoice-details">
            <div><strong>De :</strong> ' . htmlspecialchars($invoice['clinic_name']) . ', ' . htmlspecialchars($invoice['clinic_address']) . ', Tél: ' . htmlspecialchars($invoice['clinic_phone']) . '</div>
            <div><strong>Pour :</strong> ' . htmlspecialchars($invoice['first_name'] . ' ' . $invoice['last_name']) . ', ' . htmlspecialchars($invoice['address']) . ', Tél: ' . htmlspecialchars($invoice['phone']) . '</div>
        </div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($items as $item) {
            $html .= '<tr>
                <td>' . htmlspecialchars($item['description']) . '</td>
                <td>' . htmlspecialchars($item['quantity']) . '</td>
                <td>' . number_format($item['unit_price'], 2, ',', ' ') . ' MAD</td>
                <td>' . number_format($item['total_price'], 2, ',', ' ') . ' MAD</td>
            </tr>';
        }
        $html .= '</tbody></table>
        <div class="total-section">
            <p>Sous-total : ' . number_format($invoice['subtotal'], 2, ',', ' ') . ' MAD</p>
            <p>Taxe : ' . number_format($invoice['tax_amount'], 2, ',', ' ') . ' MAD</p>
            <p>Remise : ' . number_format($invoice['discount_amount'], 2, ',', ' ') . ' MAD</p>
            <p><strong>Total : ' . number_format($invoice['total_amount'], 2, ',', ' ') . ' MAD</strong></p>
        </div>
        <div style="margin-top:32px;text-align:center;color:#64748b;font-size:0.95rem;">
            Merci pour votre confiance.<br>
            Facture générée par SmileDesk.
        </div>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF (force download, one page)
        $pdf->Output('Facture-' . $invoice['invoice_number'] . '.pdf', 'I');
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}