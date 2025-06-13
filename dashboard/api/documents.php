<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../config/helpers.php'; // Change path from includes to config

header('Content-Type: application/json');

define('VALID_DOCUMENT_TYPES', [
    'prescription',
    'xray',
    'lab_result',
    'consent_form',
    'treatment_plan',
    'other'
]);

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$user_id = $_SESSION['user_id'];

switch ($method) {
    case 'GET':
        handleGetDocuments();
        break;
    case 'POST':
        handleUploadDocument();
        break;
    case 'PUT':
        handleUpdateDocument();
        break;
    case 'DELETE':
        handleDeleteDocument();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetDocuments() {
    global $pdo, $user_id;
    
    try {
        if (isset($_GET['action']) && $_GET['action'] === 'download' && isset($_GET['id'])) {
            downloadDocument($_GET['id']);
            return;
        }
        
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("
                SELECT d.*, p.first_name, p.last_name,
                       CONCAT(p.first_name, ' ', p.last_name) as patient_name,
                       u.first_name as uploaded_by_name
                FROM documents d
                LEFT JOIN patients p ON d.patient_id = p.id
                LEFT JOIN users u ON d.uploaded_by = u.id
                WHERE d.id = ? AND d.user_id = ?
            ");
            $stmt->execute([$_GET['id'], $user_id]);
            $document = $stmt->fetch();
            
            if ($document) {
                echo json_encode($document);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Document not found']);
            }
        } else {
            $stmt = $pdo->prepare("
                SELECT d.*, p.first_name, p.last_name, u.first_name as uploaded_by_name
                FROM documents d
                LEFT JOIN patients p ON d.patient_id = p.id
                LEFT JOIN users u ON d.uploaded_by = u.id
                WHERE d.user_id = ?
                ORDER BY d.created_at DESC
            ");
            $stmt->execute([$user_id]);
            $documents = $stmt->fetchAll();
            echo json_encode($documents);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleUploadDocument() {
    global $pdo, $user_id;
    
    try {
        // Validate document type
        if (!isset($_POST['type']) || !in_array($_POST['type'], VALID_DOCUMENT_TYPES)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid document type']);
            return;
        }
        
        $required_fields = ['title', 'type'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Missing required field: $field"]);
                return;
            }
        }
        
        $file_path = null;
        $file_size = null;
        $mime_type = null;
        
        // Handle file upload
        if (isset($_FILES['document_file']) && $_FILES['document_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/documents/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = pathinfo($_FILES['document_file']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['document_file']['tmp_name'], $file_path)) {
                $file_size = $_FILES['document_file']['size'];
                $mime_type = $_FILES['document_file']['type'];
                $file_path = 'uploads/documents/' . $file_name; // Store relative path
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to upload file']);
                return;
            }
        }
        
        // Handle optional patient_id and appointment_id
        $patient_id = !empty($_POST['patient_id']) ? $_POST['patient_id'] : null;
        $appointment_id = !empty($_POST['appointment_id']) ? $_POST['appointment_id'] : null;
        
        // Insert document
        $stmt = $pdo->prepare("
            INSERT INTO documents (
                user_id, patient_id, appointment_id, type, title, description,
                file_path, file_size, mime_type, uploaded_by, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $user_id,
            $patient_id,
            $appointment_id,
            $_POST['type'],
            $_POST['title'],
            $_POST['description'] ?? null,
            $file_path,
            $file_size,
            $mime_type,
            $_SESSION['user_id']
        ]);
        
        $document_id = $pdo->lastInsertId();
        
        // Log activity
        logActivity($pdo, $user_id, 'document_uploaded', 'Document uploaded', $_POST['title'] . ' was uploaded');
        
        echo json_encode([
            'success' => true,
            'message' => 'Document uploaded successfully',
            'document_id' => $document_id
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleUpdateDocument() {
    global $pdo, $user_id;
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Document ID is required']);
            return;
        }
        
        // Verify document belongs to clinic
        $stmt = $pdo->prepare("SELECT id FROM documents WHERE id = ? AND user_id = ?");
        $stmt->execute([$input['id'], $user_id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Document not found']);
            return;
        }
        
        $fields = [];
        $params = [];
        
        $allowed_fields = ['title', 'description', 'type', 'patient_id', 'appointment_id'];
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
        
        $params[] = $input['id'];
        
        $query = "UPDATE documents SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        
        echo json_encode(['success' => true, 'message' => 'Document updated successfully']);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function handleDeleteDocument() {
    global $pdo, $user_id;
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Document ID is required']);
            return;
        }
        
        // Get document info
        $stmt = $pdo->prepare("SELECT file_path FROM documents WHERE id = ? AND user_id = ?");
        $stmt->execute([$input['id'], $user_id]);
        $document = $stmt->fetch();
        
        if (!$document) {
            http_response_code(404);
            echo json_encode(['error' => 'Document not found']);
            return;
        }
        
        // Delete file from filesystem
        if ($document['file_path'] && file_exists('../' . $document['file_path'])) {
            unlink('../' . $document['file_path']);
        }
        
        // Delete document from database
        $stmt = $pdo->prepare("DELETE FROM documents WHERE id = ?");
        $stmt->execute([$input['id']]);
        
        // Log activity
        logActivity($pdo, $user_id, 'document_deleted', 'Document deleted', 'Document ID ' . $input['id'] . ' was deleted');
        
        echo json_encode(['success' => true, 'message' => 'Document deleted successfully']);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function downloadDocument($document_id) {
    global $pdo, $user_id;
    
    try {
        $stmt = $pdo->prepare("
            SELECT file_path, title, mime_type 
            FROM documents 
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$document_id, $user_id]);
        $document = $stmt->fetch();
        
        if (!$document || !$document['file_path']) {
            http_response_code(404);
            echo json_encode(['error' => 'Document not found']);
            return;
        }
        
        $file_path = '../' . $document['file_path'];
        
        if (!file_exists($file_path)) {
            http_response_code(404);
            echo json_encode(['error' => 'File not found']);
            return;
        }
        
        // Set headers for file download
        header('Content-Type: ' . ($document['mime_type'] ?? 'application/octet-stream'));
        header('Content-Disposition: attachment; filename="' . $document['title'] . '"');
        header('Content-Length: ' . filesize($file_path));
        
        // Output file
        readfile($file_path);
        exit;
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
