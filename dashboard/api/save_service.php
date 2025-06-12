<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = $_POST;

$name = trim($data['name'] ?? '');
$description = trim($data['description'] ?? '');
$category_id = $data['category'] ?? null;
$duration = $data['duration'] ?? 0;
$price = $data['price'] ?? 0;
$requires_tooth_selection = !empty($data['tooth']) ? 1 : 0;
$id = $data['id'] ?? null;

if ($name === '' || !$category_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Name and category are required']);
    exit;
}

try {
    if ($id) {
        // Update existing service
        $stmt = $pdo->prepare("UPDATE services SET name=?, description=?, category_id=?, duration=?, price=?, requires_tooth_selection=? WHERE id=? AND user_id=?");
        $stmt->execute([$name, $description, $category_id, $duration, $price, $requires_tooth_selection, $id, $user_id]);
    } else {
        // Insert new service
        $stmt = $pdo->prepare("INSERT INTO services (user_id, name, description, category_id, duration, price, requires_tooth_selection, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'active')");
        $stmt->execute([$user_id, $name, $description, $category_id, $duration, $price, $requires_tooth_selection]);
    }
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
