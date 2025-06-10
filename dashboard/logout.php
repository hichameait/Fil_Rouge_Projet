<?php
require_once 'includes/auth.php';

// Start session
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

logout();

// Redirect to login page
header('Location: login.php');
exit;
