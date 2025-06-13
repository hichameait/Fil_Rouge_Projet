<?php
// Make sure there is no whitespace or output before this file's opening <?php tag

function isLoggedIn() {
    // Check if user is logged in
    return isset($_SESSION['email']) && isset($_SESSION['auth']) && $_SESSION['auth'] === true;
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
        'first_name' => $_SESSION['first_name'] ?? ($_SESSION['name'] ?? 'User'),
        'last_name' => $_SESSION['last_name'] ?? '',
        'role' => $_SESSION['role'] ?? 'guest',
        'clinic_name' => $_SESSION['clinic_name'] ?? ''
    ];
}

function debugSession() {
    return [
        'session_id' => session_id(),
        'session_status' => session_status(),
        'session_data' => $_SESSION
    ];
}

function login($email, $password) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Set all necessary session variables
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['auth'] = true;
            $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['user_status'] = $user['status']; // Make sure this matches DB field
            $_SESSION['role'] = $user['role'];
            $_SESSION['is_logged'] = true;
            // Optionally set payment_completed if needed
            // $_SESSION['payment_completed'] = true;
            // Update last login
            $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            return true;
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
    }
    return false;
}

function logout() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        // Remove all session variables
        $_SESSION = [];
        // Destroy the session cookie if it exists
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
    if (!headers_sent()) {
        header('Location: login.php');
        exit;
    } else {
        echo "<script>window.location.href='login.php';</script>";
        exit;
    }
}

function requireRole($roles) {
    if (!isLoggedIn()) {
        if (!headers_sent()) {
            header('Location: login.php');
            exit;
        } else {
            echo "<script>window.location.href='login.php';</script>";
            exit;
        }
    }
    if (!in_array($_SESSION['role'], (array)$roles)) {
        if (!headers_sent()) {
            header('Location: index.php?error=access_denied');
            exit;
        } else {
            echo "<script>window.location.href='index.php?error=access_denied';</script>";
            exit;
        }
    }
}
?>
