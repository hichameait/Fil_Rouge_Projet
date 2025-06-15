<?php
require_once '../../dashboard/config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$plan_id = isset($_POST['plan_id']) ? (int)$_POST['plan_id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);

try {
    if ($action === 'get' && $plan_id) {
        $stmt = $pdo->prepare("SELECT * FROM subscription_plans WHERE id = ?");
        $stmt->execute([$plan_id]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$plan) throw new Exception('Plan introuvable');
        $plan['features'] = $plan['features'] ? json_decode($plan['features'], true) : [];
        echo json_encode($plan);
        exit;
    }
    if ($action === 'create') {
        $features = $_POST['features'] ?? [];
        $stmt = $pdo->prepare("INSERT INTO subscription_plans (name, description, duration_months, price, features, is_active) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->execute([
            $_POST['name'],
            $_POST['description'],
            (int)$_POST['duration_months'],
            (float)$_POST['price'],
            json_encode(array_filter($features))
        ]);
        echo json_encode(['success' => true, 'message' => 'Plan créé avec succès']);
        exit;
    }
    if ($action === 'update' && $plan_id) {
        $features = $_POST['features'] ?? [];
        $stmt = $pdo->prepare("UPDATE subscription_plans SET name=?, description=?, duration_months=?, price=?, features=? WHERE id=?");
        $stmt->execute([
            $_POST['name'],
            $_POST['description'],
            (int)$_POST['duration_months'],
            (float)$_POST['price'],
            json_encode(array_filter($features)),
            $plan_id
        ]);
        echo json_encode(['success' => true, 'message' => 'Plan mis à jour avec succès']);
        exit;
    }
    if ($action === 'toggle' && $plan_id) {
        $pdo->prepare("UPDATE subscription_plans SET is_active = IF(is_active=1,0,1) WHERE id=?")->execute([$plan_id]);
        echo json_encode(['success' => true, 'message' => 'Statut du plan mis à jour']);
        exit;
    }
    if ($action === 'delete' && $plan_id) {
        $pdo->prepare("DELETE FROM subscription_plans WHERE id=?")->execute([$plan_id]);
        echo json_encode(['success' => true, 'message' => 'Plan supprimé avec succès']);
        exit;
    }
    throw new Exception('Action non valide');
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
