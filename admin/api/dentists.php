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
$id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);

try {
    if ($action === 'get' && $id) {
        $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, status FROM users WHERE id = ? AND role = 'dentist'");
        $stmt->execute([$id]);
        $dentist = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$dentist) throw new Exception('Dentiste introuvable');
        echo json_encode($dentist);
        exit;
    }
    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, role, status, password) VALUES (?, ?, ?, 'dentist', ?, ?)");
        $stmt->execute([
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['status'],
            password_hash('changeme', PASSWORD_DEFAULT)
        ]);
        echo json_encode(['success' => true, 'message' => 'Dentiste ajouté (mot de passe: changeme)']);
        exit;
    }
    if ($action === 'update' && $id) {
        $stmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, email=?, status=? WHERE id=? AND role='dentist'");
        $stmt->execute([
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['status'],
            $id
        ]);
        echo json_encode(['success' => true, 'message' => 'Dentiste mis à jour']);
        exit;
    }
    if ($action === 'delete' && $id) {
        $pdo->prepare("DELETE FROM users WHERE id=? AND role='dentist'")->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Dentiste supprimé']);
        exit;
    }
    throw new Exception('Action non valide');
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
