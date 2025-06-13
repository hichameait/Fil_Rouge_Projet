<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['email']) || !isset($input['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password are required']);
        exit;
    }
    
    $stmt = $pdo->prepare("
        SELECT u.*, c.id as user_id, c.name as clinic_name 
        FROM users u
        JOIN clinics c ON u.user_id = c.id
        WHERE u.email = ? AND u.status = 'active'
    ");
    $stmt->execute([$input['email']]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($input['password'], $user['password'])) {
        // Start new session
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        
        // Set session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['clinic_name'] = $user['clinic_name'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $user['id'],
                'name' => $_SESSION['name'],
                'role' => $user['role'],
                'clinic_name' => $user['clinic_name']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}