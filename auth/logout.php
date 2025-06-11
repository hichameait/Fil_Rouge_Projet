<?php
require_once '../dashboard/includes/auth.php';

session_start();

$_SESSION = [];

session_destroy();

logout();

header('Location: ./login.php');
exit;
