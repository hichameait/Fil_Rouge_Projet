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
    // 1. Get all base services with dentist's custom price if exists
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
            ON dsp.base_service_id = bs.id AND dsp.user_id = ?
        LEFT JOIN service_categories sc
            ON bs.category_id = sc.id
        WHERE bs.is_active = 1
    ");
    $stmt->execute([$user_id]);
    $base_services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Get dentist's own custom services (from services table)
    $stmt2 = $pdo->prepare("
        SELECT 
            s.id AS custom_service_id,
            s.name,
            s.description,
            s.duration,
            s.price,
            s.requires_tooth_selection,
            s.status,
            sc.name AS category_name
        FROM services s
        LEFT JOIN service_categories sc
            ON s.category_id = sc.id
        WHERE s.user_id = ? AND s.status = 'active'
    ");
    $stmt2->execute([$user_id]);
    $custom_services = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // 3. Merge and format the result
    $result = [];

    foreach ($base_services as $bs) {
        $result[] = [
            'type' => 'base',
            'id' => $bs['base_service_id'],
            'name' => $bs['name'],
            'description' => $bs['description'],
            'duration' => $bs['duration'],
            'requires_tooth_selection' => $bs['requires_tooth_selection'],
            'category' => $bs['category_name'],
            'price' => $bs['custom_price'] !== null ? $bs['custom_price'] : null // null means not set by dentist yet
        ];
    }

    foreach ($custom_services as $cs) {
        $result[] = [
            'type' => 'custom',
            'id' => $cs['custom_service_id'],
            'name' => $cs['name'],
            'description' => $cs['description'],
            'duration' => $cs['duration'],
            'requires_tooth_selection' => $cs['requires_tooth_selection'],
            'category' => $cs['category_name'],
            'price' => $cs['price']
        ];
    }

    echo json_encode($result);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
