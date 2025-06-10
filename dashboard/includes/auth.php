<?php
function isLoggedIn() {
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['clinic_id'])) {
        return false;
    }

    // Validate session data
    if (empty($_SESSION['user_id']) || empty($_SESSION['clinic_id'])) {
        return false;
    }

    return true;
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return [
            'first_name' => 'Guest',
            'last_name' => '',
            'role' => 'guest'
        ];
    }
    
    return [
        'user_id' => $_SESSION['user_id'] ?? null,
        'clinic_id' => $_SESSION['clinic_id'] ?? null,
        'first_name' => $_SESSION['first_name'] ?? 'User',
        'last_name' => $_SESSION['last_name'] ?? '',
        'role' => $_SESSION['role'] ?? 'guest',
        'clinic_name' => $_SESSION['clinic_name'] ?? ''
    ];
}

// Add this debug function
function debugSession() {
    return [
        'session_id' => session_id(),
        'session_status' => session_status(),
        'session_data' => $_SESSION
    ];
}

function login($email, $password) {
    $user = fetchOne(
        "SELECT * FROM users WHERE email = ? AND status = 'active'",
        [$email]
    );
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['clinic_id'] = $user['clinic_id'];
        $_SESSION['role'] = $user['role'];
        
        // Update last login
        executeQuery(
            "UPDATE users SET last_login = NOW() WHERE id = ?",
            [$user['id']]
        );
        
        return true;
    }
    
    return false;
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

function requireRole($roles) {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
    
    if (!in_array($_SESSION['role'], (array)$roles)) {
        header('Location: index.php?error=access_denied');
        exit;
    }
}
?>
