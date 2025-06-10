<?php
// Appointment constants
if (!defined('VALID_APPOINTMENT_STATUSES')) {
    define('VALID_APPOINTMENT_STATUSES', [
        'scheduled',
        'confirmed',
        'in_progress',
        'completed',
        'cancelled',
        'no_show'
    ]);
}

// Activity types
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