<?php
// Include database connection
require_once '../config/database.php';

// Set header to return JSON
header('Content-Type: application/json');

try {
    // Get all procedures for dropdown
    $stmt = $pdo->query("SELECT id, name, duration, price FROM procedures ORDER BY name");
    $procedures = $stmt->fetchAll();
    
    echo json_encode($procedures);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
