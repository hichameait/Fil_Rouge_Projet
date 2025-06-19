<?php
require_once __DIR__ . '/constants.php';

if (!function_exists('logActivity')) {
    function logActivity($pdo, $user_id, $type, $title, $description) {
        $valid_types = defined('VALID_ACTIVITY_TYPES') ? VALID_ACTIVITY_TYPES : [];
        if (!in_array($type, $valid_types)) {
            error_log("Invalid activity type: $type");
            return false;
        }
        try {
            $stmt = $pdo->prepare("
                INSERT INTO activities (user_id, type, title, description, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            return $stmt->execute([$user_id, $type, $title, $description]);
        } catch (PDOException $e) {
            error_log("Error logging activity: " . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('validateDate')) {
    function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}