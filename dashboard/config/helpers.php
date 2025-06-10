<?php
if (!defined('VALID_ACTIVITY_TYPES')) {
    define('VALID_ACTIVITY_TYPES', [
        'appointment_scheduled',
        'appointment_updated',
        'appointment_cancelled',
        'patient_added',
        'patient_updated',
        'patient_deleted',
        'payment_received',
        'document_uploaded',
        'document_deleted'
    ]);
}

if (!function_exists('logActivity')) {
    function logActivity($pdo, $clinic_id, $type, $title, $description) {
        if (!in_array($type, VALID_ACTIVITY_TYPES)) {
            error_log("Invalid activity type: $type");
            return false;
        }
        
        try {
            $stmt = $pdo->prepare("
                INSERT INTO activities (clinic_id, type, title, description, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            return $stmt->execute([$clinic_id, $type, $title, $description]);
        } catch (PDOException $e) {
            error_log("Error logging activity: " . $e->getMessage());
            return false;
        }
    }
}

// Add other helper functions with existence checks
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