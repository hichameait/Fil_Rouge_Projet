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
$clinic_id = $_SESSION['clinic_id'];

switch ($method) {
    case 'GET':
        handleGetPatients();
        break;
    case 'POST':
        handleCreatePatient();
        break;
    case 'PUT':
        handleUpdatePatient();
        break;
    case 'DELETE':
        handleDeletePatient();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetPatients() {
    global $pdo, $clinic_id;
    
    try {
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ? AND clinic_id = ?");
            $stmt->execute([$_GET['id'], $clinic_id]);
            $patient = $stmt->fetch();
            
            if ($patient) {
                echo json_encode($patient);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Patient not found']);
            }
        } else {
            $stmt = $pdo->prepare("SELECT id, first_name, last_name FROM patients WHERE clinic_id = ? AND status = 'active' ORDER BY first_name, last_name");
            $stmt->execute([$clinic_id]);
            $patients = $stmt->fetchAll();
            echo json_encode($patients);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleCreatePatient() {
    global $pdo, $clinic_id;
    
    try {
        $required_fields = ['first_name', 'last_name', 'phone'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Missing required field: $field"]);
                return;
            }
        }
        
        // Generate patient number
        $patient_number = generatePatientNumber($clinic_id);
        
        $stmt = $pdo->prepare("
            INSERT INTO patients (
                clinic_id, patient_number, first_name, last_name, email, phone, 
                date_of_birth, gender, address, medical_history, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $clinic_id,
            $patient_number,
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'] ?? null,
            $_POST['phone'],
            $_POST['date_of_birth'] ?? null,
            $_POST['gender'] ?? null,
            $_POST['address'] ?? null,
            $_POST['medical_history'] ?? null
        ]);
        
        $patient_id = $pdo->lastInsertId();
        
        // Log activity
        $stmt = $pdo->prepare("
            INSERT INTO activities (clinic_id, type, title, description, created_at)
            VALUES (?, 'patient_added', 'New patient registered', ?, NOW())
        ");
        $stmt->execute([$clinic_id, $_POST['first_name'] . ' ' . $_POST['last_name'] . ' was registered']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Patient created successfully',
            'patient_id' => $patient_id
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleUpdatePatient() {
    global $pdo, $clinic_id;
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Patient ID is required']);
            return;
        }
        
        // Verify patient belongs to clinic
        $stmt = $pdo->prepare("SELECT id FROM patients WHERE id = ? AND clinic_id = ?");
        $stmt->execute([$input['id'], $clinic_id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Patient not found']);
            return;
        }
        
        $fields = [];
        $params = [];
        
        $allowed_fields = ['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender', 'address', 'medical_history', 'status'];
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
        
        $query = "UPDATE patients SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        
        echo json_encode(['success' => true, 'message' => 'Patient updated successfully']);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleDeletePatient() {
    global $pdo, $clinic_id;
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Patient ID is required']);
            return;
        }
        
        // Verify patient belongs to clinic
        $stmt = $pdo->prepare("SELECT id FROM patients WHERE id = ? AND clinic_id = ?");
        $stmt->execute([$input['id'], $clinic_id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Patient not found']);
            return;
        }
        
        // Soft delete (mark as inactive)
        $stmt = $pdo->prepare("UPDATE patients SET status = 'inactive', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$input['id']]);
        
        echo json_encode(['success' => true, 'message' => 'Patient deleted successfully']);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function generatePatientNumber($clinic_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM patients WHERE clinic_id = ?");
    $stmt->execute([$clinic_id]);
    $count = $stmt->fetch()['count'];
    
    return 'P' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
}
?>
