<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    // Get all users with role 'dentist' and status 'active'
    $stmt = $pdo->prepare("SELECT id, first_name, last_name FROM users WHERE role = 'dentist' AND status = 'active'");
    $stmt->execute();
    $dentists = $stmt->fetchAll();
    echo json_encode($dentists);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
