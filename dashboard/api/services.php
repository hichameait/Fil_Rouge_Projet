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

try {
    // Get all base services (global and custom) with dentist's custom price if exists
    $stmt = $pdo->prepare("
        SELECT 
            bs.id AS base_service_id,
            bs.name,
            bs.description,
            bs.duration,
            bs.requires_tooth_selection,
            dsp.price AS custom_price,
            bs.is_active,
            sc.name AS category_name
        FROM base_services bs
        LEFT JOIN dentist_service_prices dsp 
            ON dsp.base_service_id = bs.id 
            AND dsp.user_id = ? 
            AND dsp.is_active = 1
        LEFT JOIN service_categories sc
            ON bs.category_id = sc.id
        WHERE bs.is_active = 1
        ORDER BY sc.name, bs.name
    ");
    $stmt->execute([$user_id]);
    $base_services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the result
    $result = [];
    foreach ($base_services as $bs) {
        $result[] = [
            'id' => $bs['base_service_id'],
            'name' => $bs['name'],
            'description' => $bs['description'],
            'duration' => $bs['duration'],
            'requires_tooth_selection' => $bs['requires_tooth_selection'],
            'category' => $bs['category_name'],
            'price' => $bs['custom_price'], // Only from dentist_service_prices
            'is_custom' => isset($bs['created_by']) && $bs['created_by'] ? true : false
        ];
    }

    echo json_encode($result);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
