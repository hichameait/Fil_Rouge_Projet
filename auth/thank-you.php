<?php
    session_start();

    require_once '../dashboard/config/database.php';
    require_once '../dashboard/includes/auth.php';

    if (isLoggedIn()) {
        header('Location: ../dashboard/index.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>