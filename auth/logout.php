<?php
require_once '../dashboard/includes/auth.php';

session_start();

$_SESSION = [];

// Destroy the session
session_destroy();

logout();

// Redirect to login page
header('Location: login.php');
exit;
