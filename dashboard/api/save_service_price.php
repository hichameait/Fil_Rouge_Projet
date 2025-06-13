<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed, use POST']);
    exit;
}

// Support both form-data and JSON input
$input = $_POST;
if (empty($input)) {
    $json = file_get_contents('php://input');
    $input = json_decode($json, true) ?: [];
}

$user_id = $_SESSION['user_id'];
$base_service_id = $input['base_service_id'] ?? null;
$price = $input['price'] ?? null;
if (!$base_service_id || $price === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing data']);
    exit;
}
try {
    // Check if price already exists
    $exists = fetchOne("SELECT id FROM dentist_service_prices WHERE user_id = ? AND base_service_id = ?", [$user_id, $base_service_id]);
    if ($exists) {
        executeQuery("UPDATE dentist_service_prices SET price = ?, is_active = 1, updated_at = NOW() WHERE id = ?", [$price, $exists['id']]);
    } else {
        executeQuery("INSERT INTO dentist_service_prices (user_id, base_service_id, price, is_active, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(), NOW())", [$user_id, $base_service_id, $price]);
    }
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
