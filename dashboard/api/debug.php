<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

echo json_encode([
    'is_logged_in' => isLoggedIn(),
    'session_debug' => debugSession(),
    'cookies' => $_COOKIE,
    'server' => [
        'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'],
        'REQUEST_URI' => $_SERVER['REQUEST_URI'],
        'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'],
        'HTTP_HOST' => $_SERVER['HTTP_HOST'],
        'HTTPS' => isset($_SERVER['HTTPS'])
    ]
]);