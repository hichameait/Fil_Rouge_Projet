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
        // Update existing base_service (custom or global)
        $stmt = $pdo->prepare("UPDATE base_services SET name=?, description=?, category_id=?, duration=?, requires_tooth_selection=? WHERE id=?");
        $stmt->execute([$name, $description, $category_id, $duration, $requires_tooth_selection, $id]);
        // Update price for this dentist
        $exists = fetchOne("SELECT id FROM dentist_service_prices WHERE user_id = ? AND base_service_id = ?", [$user_id, $id]);
        if ($exists) {
            $stmt = $pdo->prepare("UPDATE dentist_service_prices SET price=? WHERE id=?");
            $stmt->execute([$price, $exists['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO dentist_service_prices (user_id, base_service_id, price, is_active, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(), NOW())");
            $stmt->execute([$user_id, $id, $price]);
        }
    } else {
        // Insert new custom base_service (created_by = dentist)
        $stmt = $pdo->prepare("INSERT INTO base_services (category_id, name, description, duration, requires_tooth_selection, is_active, created_at, updated_at, created_by) VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW(), ?)");
        $stmt->execute([$category_id, $name, $description, $duration, $requires_tooth_selection, $user_id]);
        $base_service_id = $pdo->lastInsertId();
        // Insert price for this dentist
        $stmt = $pdo->prepare("INSERT INTO dentist_service_prices (user_id, base_service_id, price, is_active, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(), NOW())");
        $stmt->execute([$user_id, $base_service_id, $price]);
    }
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
