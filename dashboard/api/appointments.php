<?php
// Include required files
require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/auth.php';

// Set header to return JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];
$user_id = $_SESSION['user_id'];

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    case 'POST':
        handlePost($pdo);
        break;
    case 'PUT':
        handlePut($pdo);
        break;
    case 'DELETE':
        handleDelete($pdo);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGet($pdo) {
    global $user_id;
    try {
        if (isset($_GET['id'])) {
            // Get specific appointment with related data
            $stmt = $pdo->prepare("
                SELECT a.*, 
                       p.first_name, p.last_name, p.phone,
                       bs.name as service_name,
                       u.first_name as dentist_first_name, 
                       u.last_name as dentist_last_name
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN base_services bs ON a.base_service_id = bs.id
                JOIN users u ON a.dentist_id = u.id
                WHERE a.id = ? AND a.user_id = ?
            ");
            $stmt->execute([$_GET['id'], $user_id]);
            $appointment = $stmt->fetch();
            if ($appointment) {
                echo json_encode(['success' => true, 'appointment' => $appointment]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Appointment not found']);
            }
        } else {
            // Get all appointments with filters
            $where_conditions = ["a.user_id = ?"];
            $params = [$user_id];
            if (isset($_GET['date'])) {
                $where_conditions[] = "DATE(a.appointment_date) = ?";
                $params[] = $_GET['date'];
            }
            if (isset($_GET['status'])) {
                $where_conditions[] = "a.status = ?";
                $params[] = $_GET['status'];
            }
            if (isset($_GET['dentist_id'])) {
                $where_conditions[] = "a.dentist_id = ?";
                $params[] = $_GET['dentist_id'];
            }
            $query = "SELECT a.*, p.first_name, p.last_name, bs.name as service_name, u.first_name as dentist_name
                      FROM appointments a
                      JOIN patients p ON a.patient_id = p.id
                      JOIN base_services bs ON a.base_service_id = bs.id
                      JOIN users u ON a.dentist_id = u.id
                      WHERE " . implode(' AND ', $where_conditions) . "
                      ORDER BY a.appointment_date, a.appointment_time";
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            $appointments = $stmt->fetchAll();
            echo json_encode($appointments);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handlePost($pdo) {
    global $user_id;
    try {
        $required_fields = ['patient_id', 'service_id', 'appointment_date', 'appointment_time'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Missing required field: $field"]);
                return;
            }
        }
        // Get service details (remove user_id filter if services are global)
        $stmt = $pdo->prepare("
            SELECT duration FROM base_services WHERE id = ?
        ");
        $stmt->execute([$_POST['service_id']]);
        $service = $stmt->fetch();
        if (!$service) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid service']);
            return;
        }
        // Check for conflicts
        $stmt = $pdo->prepare("
            SELECT id FROM appointments 
            WHERE dentist_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'cancelled'
        ");
        $stmt->execute([$user_id, $_POST['appointment_date'], $_POST['appointment_time']]);
        $conflict = $stmt->fetch();
        if ($conflict) {
            http_response_code(400);
            echo json_encode(['error' => 'Time slot already booked']);
            return;
        }
        // Insert appointment (update columns to use base_service_id)
        $stmt = $pdo->prepare("
            INSERT INTO appointments (
                user_id, patient_id, base_service_id, dentist_id, appointment_date, 
                appointment_time, duration, selected_teeth, notes, status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'scheduled', NOW())
        ");
        $stmt->execute([
            $user_id,
            $_POST['patient_id'],
            $_POST['service_id'], // this should be base_service_id from the form
            $user_id, // dentist_id is the logged-in user
            $_POST['appointment_date'],
            $_POST['appointment_time'],
            $service['duration'],
            $_POST['selected_teeth'] ?? null,
            $_POST['notes'] ?? ''
        ]);
        $appointment_id = $pdo->lastInsertId();
        // Log activity using the helper function
        logActivity(
            $pdo,
            $user_id,
            'appointment_scheduled',
            'New appointment scheduled',
            "Appointment scheduled for {$_POST['appointment_date']} at {$_POST['appointment_time']}"
        );
        echo json_encode([
            'success' => true,
            'message' => 'Appointment created successfully',
            'appointment_id' => $appointment_id
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handlePut($pdo) {
    global $user_id;
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        // Validate status if provided
        if (isset($input['status']) && !in_array($input['status'], VALID_APPOINTMENT_STATUSES)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid status value']);
            return;
        }
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Appointment ID is required']);
            return;
        }
        // Verify appointment belongs to user
        $stmt = $pdo->prepare("SELECT id FROM appointments WHERE id = ? AND user_id = ?");
        $stmt->execute([$input['id'], $user_id]);
        $appointment = $stmt->fetch();
        if (!$appointment) {
            http_response_code(404);
            echo json_encode(['error' => 'Appointment not found']);
            return;
        }
        // Build update query
        $fields = [];
        $params = [];
        $allowed_fields = ['appointment_date', 'appointment_time', 'status', 'notes', 'selected_teeth'];
        foreach ($allowed_fields as $field) {
            if (isset($input[$field])) {
                $fields[] = "$field = ?";
                $params[] = $input[$field];
            }
        }
        if (empty($fields)) {
            http_response_code(400);
            echo json_encode(['error' => 'No fields to update']);
            return;
        }
        $fields[] = "updated_at = NOW()";
        $params[] = $input['id'];
        $query = "UPDATE appointments SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        // Log activity
        $stmt = $pdo->prepare("INSERT INTO activities (user_id, type, title, description, created_at) VALUES (?, 'appointment_updated', 'Appointment updated', CONCAT('Appointment #', ?, ' was updated'), NOW())");
        $stmt->execute([$user_id, $input['id']]);
        echo json_encode(['success' => true, 'message' => 'Appointment updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleDelete($pdo) {
    global $user_id;
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Appointment ID is required']);
            return;
        }
        // Verify appointment belongs to user
        $stmt = $pdo->prepare("SELECT id FROM appointments WHERE id = ? AND user_id = ?");
        $stmt->execute([$input['id'], $user_id]);
        $appointment = $stmt->fetch();
        if (!$appointment) {
            http_response_code(404);
            echo json_encode(['error' => 'Appointment not found']);
            return;
        }
        // Soft delete (mark as cancelled)
        $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$input['id']]);
        // Log activity
        $stmt = $pdo->prepare("INSERT INTO activities (user_id, type, title, description, created_at) VALUES (?, 'appointment_cancelled', 'Appointment cancelled', CONCAT('Appointment #', ?, ' was cancelled'), NOW())");
        $stmt->execute([$user_id, $input['id']]);
        echo json_encode(['success' => true, 'message' => 'Appointment cancelled successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
