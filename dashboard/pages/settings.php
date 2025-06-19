<?php

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'guest';

// Check if settings row exists, if not, create it
$settings = fetchOne(
    "SELECT * FROM settings WHERE user_id = ?",
    [$user_id]
);

if (!$settings) {
    // Get next id for settings (if id is not AUTO_INCREMENT)
    $stmt = $pdo->query("SELECT MAX(id) AS max_id FROM settings");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $nextId = $row && $row['max_id'] ? ($row['max_id'] + 1) : 1;

    // Insert with explicit id
    $stmt = $pdo->prepare("INSERT INTO settings (id, user_id, clinic_name, clinic_address, clinic_phone, clinic_email, clinic_website, clinic_logo_url, clinic_description, working_hours, updated_at) VALUES (?, ?, '', '', '', '', '', '', '', '{}', NOW())");
    $stmt->execute([$nextId, $user_id]);
    // Fetch the newly created row
    $settings = fetchOne("SELECT * FROM settings WHERE user_id = ?", [$user_id]);
}

// Fetch all users for this clinic, including the current user even if clinic_id is NULL
try {
    // Check if 'clinic_id' column exists in users table
    $columns = $pdo->query("SHOW COLUMNS FROM users LIKE 'clinic_id'")->fetch();
    if ($columns) {
        // If clinic_id exists, use it for filtering
        $users = fetchAll(
            "SELECT * FROM users WHERE (clinic_id = ? OR id = ?) ORDER BY role, first_name",
            [$settings['id'], $user_id]
        );
    } else {
        // If clinic_id does not exist, just fetch users for this user_id
        $users = fetchAll(
            "SELECT * FROM users WHERE id = ? ORDER BY role, first_name",
            [$user_id]
        );
    }
} catch (Exception $e) {
    // Fallback: fetch only the current user
    $users = fetchAll(
        "SELECT * FROM users WHERE id = ? ORDER BY role, first_name",
        [$user_id]
    );
}

$success_message = $error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'update_clinic':
            updateClinicInfo();
            break;
        case 'update_automation':
            updateAutomationSettings();
            break;
        case 'add_user':
            addUser();
            break;
        case 'update_user':
            updateUser();
            break;
        case 'delete_user':
            deleteUser();
            break;
        case 'update_profile':
            updateDentistProfile();
            break;
    }
    // Always reload settings after any update
    $settings = fetchOne("SELECT * FROM settings WHERE user_id = ?", [$user_id]);
}

// Handle logo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['clinic_logo_file']) && $_FILES['clinic_logo_file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $ext = pathinfo($_FILES['clinic_logo_file']['name'], PATHINFO_EXTENSION);
    $filename = 'clinic_logo_' . $user_id . '_' . time() . '.' . $ext;
    $targetPath = $uploadDir . $filename;
    if (move_uploaded_file($_FILES['clinic_logo_file']['tmp_name'], $targetPath)) {
        $logoUrl = './uploads/' . $filename;
        $stmt = $pdo->prepare("UPDATE settings SET clinic_logo_url = ? WHERE user_id = ?");
        $stmt->execute([$logoUrl, $user_id]);
        // Update $settings for immediate display
        $settings['clinic_logo_url'] = $logoUrl;
        $success_message = "Logo uploaded successfully!";
    } else {
        $error_message = "Failed to upload logo.";
    }
}

function post_val($key, $default = '') {
    return isset($_POST[$key]) ? $_POST[$key] : $default;
}

function updateClinicInfo() {
    global $pdo, $user_id, $success_message, $error_message;
    try {
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $working_hours = [];
        foreach ($days as $day) {
            $working_hours[$day] = [
                'open' => post_val($day . '_open', '09:00'),
                'close' => post_val($day . '_close', '17:00'),
                'closed' => isset($_POST[$day . '_closed'])
            ];
        }

        $stmt = $pdo->prepare("
            UPDATE settings SET 
                clinic_name = ?, clinic_address = ?, clinic_phone = ?, clinic_email = ?, clinic_website = ?,
                clinic_logo_url = ?, clinic_description = ?, working_hours = ?, updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([
            post_val('clinic_name'),
            post_val('clinic_address'),
            post_val('clinic_phone'),
            post_val('clinic_email'),
            post_val('clinic_website'),
            post_val('clinic_logo_url'),
            post_val('clinic_description'),
            json_encode($working_hours),
            $user_id
        ]);

        // Handle additional dentist profile fields if role is dentist
        if ($_SESSION['role'] === 'dentist') {
            $certifications = isset($_POST['certifications']) ? json_encode($_POST['certifications']) : '[]';
            $languages = [];
            if (isset($_POST['languages']) && isset($_POST['language_levels'])) {
                foreach ($_POST['languages'] as $i => $lang) {
                    if (!empty($lang)) {
                        $languages[] = [
                            'language' => $lang,
                            'level' => $_POST['language_levels'][$i] ?? 'basic'
                        ];
                    }
                }
            }

            $stmt = $pdo->prepare("
                UPDATE settings SET 
                    presentation = ?,
                    certifications = ?,
                    languages_spoken = ?
                WHERE user_id = ?
            ");
            $stmt->execute([
                $_POST['presentation'] ?? '',
                $certifications,
                json_encode($languages),
                $user_id
            ]);
        }

        // Automatically generate and update the website URL if not set or empty
        $stmt = $pdo->prepare("SELECT clinic_website FROM settings WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $current_website = $stmt->fetchColumn();

        if (empty($current_website)) {
            // Build the website URL with the correct folder path
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            // Get the base path up to /Fil_Rouge_Projet
            $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
            $basePath = '';
            if (preg_match('#/(Fil_Rouge_Projet)(/|$)#', $scriptDir, $matches, PREG_OFFSET_CAPTURE)) {
                $basePath = substr($scriptDir, 0, $matches[0][1] + strlen($matches[1][0]));
            }
            $basePath = rtrim($basePath, '/');
            $profileUrl = $protocol . $host . $basePath . '/profile.php?id=' . $user_id;

            // Update the settings with the generated website
            $stmt = $pdo->prepare("UPDATE settings SET clinic_website = ? WHERE user_id = ?");
            $stmt->execute([$profileUrl, $user_id]);
        }

        $success_message = "Settings updated successfully!";
    } catch (Exception $e) {
        $error_message = "Error updating settings: " . $e->getMessage();
    }
}

function updateAutomationSettings() {
    global $pdo, $user_id, $success_message, $error_message;
    try {
        $automation_settings = [
            // Send to patients
            'send_email_enabled' => isset($_POST['notifications']['send_email']),
            'send_sms_enabled' => isset($_POST['notifications']['send_sms']),
            'send_whatsapp_enabled' => isset($_POST['notifications']['send_whatsapp']),
            
            // Receive as dentist
            'receive_email_enabled' => isset($_POST['notifications']['receive_email']),
            'receive_sms_enabled' => isset($_POST['notifications']['receive_sms']),
            'receive_whatsapp_enabled' => isset($_POST['notifications']['receive_whatsapp']),
            
            // Existing settings
            'sms_reminder_time' => post_val('sms_reminder_time', '24'),
            'email_notifications_enabled' => isset($_POST['email_notifications_enabled']),
            'email_appointment_confirmation' => isset($_POST['email_appointment_confirmation']),
            'email_appointment_reminder' => isset($_POST['email_appointment_reminder']),
            'email_payment_receipt' => isset($_POST['email_payment_receipt']),
            'email_treatment_summary' => isset($_POST['email_treatment_summary']),
            'email_custom_template' => isset($_POST['email_custom_template']),
        ];
        
        $stmt = $pdo->prepare("
            UPDATE settings SET 
                automation_settings = ?, 
                updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([
            json_encode($automation_settings),
            $user_id
        ]);
        $success_message = "Notification settings updated successfully!";
    } catch (Exception $e) {
        $error_message = "Error updating notification settings: " . $e->getMessage();
    }
}

function addUser() {
    global $pdo, $user_id, $success_message, $error_message, $settings;
    $first_name = trim($_POST['add_first_name'] ?? '');
    $last_name = trim($_POST['add_last_name'] ?? '');
    $email = trim($_POST['add_email'] ?? '');
    $role = $_POST['add_role'] ?? 'assistant';
    $password = $_POST['add_password'] ?? '';
    // Use the current settings row id as clinic_id
    $clinic_id = $settings['id'];
    if (!$first_name || !$email || !$password) {
        $error_message = "First name, email, and password are required.";
        return;
    }
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $error_message = "A user with this email already exists.";
        return;
    }
    try {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, role, status, created_at, clinic_id) VALUES (?, ?, ?, ?, ?, 'active', NOW(), ?)");
        $stmt->execute([$first_name, $last_name, $email, $hashed, $role, $clinic_id]);
        $success_message = "User added successfully!";
    } catch (Exception $e) {
        $error_message = "Error adding user: " . $e->getMessage();
    }
}

function updateSettings() {
    global $pdo, $user_id;
    
    try {
        if ($_SESSION['role'] === 'admin') {
            // Update global SMTP settings
            $smtp_settings = json_encode([
                'host' => $_POST['smtp']['host'] ?? '',
                'port' => $_POST['smtp']['port'] ?? '',
                'username' => $_POST['smtp']['username'] ?? '',
                'password' => $_POST['smtp']['password'] ?? '',
                'encryption' => $_POST['smtp']['encryption'] ?? 'tls'
            ]);
            
            $stmt = $pdo->prepare("
                INSERT INTO global_settings (smtp_settings) VALUES (?)
                ON DUPLICATE KEY UPDATE smtp_settings = VALUES(smtp_settings)
            ");
            $stmt->execute([$smtp_settings]);
        }

        // Update clinic-specific settings
        $automation_settings = [
            'email_enabled' => isset($_POST['automation']['email_enabled']),
            'sms_enabled' => isset($_POST['automation']['sms_enabled'])
        ];

        $stmt = $pdo->prepare("
            UPDATE settings SET 
                automation_settings = ?,
                updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([json_encode($automation_settings), $user_id]);

        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

function updateUser() {
    global $pdo, $success_message, $error_message;
    try {
        $userId = $_POST['user_id'] ?? '';
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? '';
        $status = $_POST['status'] ?? 'active';

        $stmt = $pdo->prepare("
            UPDATE users 
            SET first_name = ?, last_name = ?, email = ?, role = ?, status = ?
            WHERE id = ?
        ");
        $stmt->execute([$firstName, $lastName, $email, $role, $status, $userId]);
        
        $success_message = "User updated successfully!";
    } catch (Exception $e) {
        $error_message = "Error updating user: " . $e->getMessage();
    }
}

function deleteUser() {
    global $pdo, $success_message, $error_message;
    try {
        $userId = $_POST['user_id'] ?? '';
        
        if (!$userId) {
            throw new Exception("User ID is required");
        }

        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        
        $success_message = "User deleted successfully!";
    } catch (Exception $e) {
        $error_message = "Error deleting user: " . $e->getMessage();
    }
}

function updateDentistProfile() {
    global $pdo, $user_id, $success_message, $error_message;
    try {
        $certifications = isset($_POST['certifications']) ? json_encode($_POST['certifications']) : '[]';
        $languages = [];
        if (isset($_POST['languages']) && isset($_POST['language_levels'])) {
            foreach ($_POST['languages'] as $i => $lang) {
                if (!empty($lang)) {
                    $languages[] = [
                        'language' => $lang,
                        'level' => $_POST['language_levels'][$i] ?? 'basic'
                    ];
                }
            }
        }
        $stmt = $pdo->prepare("
            UPDATE settings SET 
                presentation = ?,
                certifications = ?,
                languages_spoken = ?,
                updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([
            $_POST['presentation'] ?? '',
            $certifications,
            json_encode($languages),
            $user_id
        ]);
        $success_message = "Profile updated successfully!";
    } catch (Exception $e) {
        $error_message = "Error updating profile: " . $e->getMessage();
    }
}

// Parse working hours and automation settings (always after $settings is up-to-date)
$working_hours = json_decode($settings['working_hours'] ?? '{}', true);

// Always parse automation_settings after $settings is reloaded
$default_automation_settings = [
    'sms_reminders_enabled' => false,
    'sms_reminder_time' => '24',
    'sms_provider' => 'twilio',
    'sms_sender_name' => '',
    'sms_api_key' => '',
    'email_notifications_enabled' => false,
    'email_appointment_confirmation' => false,
    'email_appointment_reminder' => false,
    'email_payment_receipt' => false,
    'email_treatment_summary' => false,
    'email_custom_template' => false,
    'chatbot_enabled' => false,
    // Add all notification keys you use in the form, default to false
    'send_email_enabled' => false,
    'send_sms_enabled' => false,
    'send_whatsapp_enabled' => false,
    'receive_email_enabled' => false,
    'receive_sms_enabled' => false,
    'receive_whatsapp_enabled' => false,
];
$automation_settings = json_decode($settings['automation_settings'] ?? '{}', true);
if (!is_array($automation_settings)) $automation_settings = [];
$automation_settings = array_merge($default_automation_settings, $automation_settings);
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Settings</h2>
            <p class="text-gray-600">Manage your clinic settings and preferences</p>
        </div>
    </div>

    <?php if ($success_message): ?>
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4"><?= $success_message ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-4"><?= $error_message ?></div>
    <?php endif; ?>

    <!-- Settings Tabs -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6">
                <button type="button" class="settings-tab-btn py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600" data-tab="clinic">
                    <i class="fas fa-building mr-2"></i>Clinic Information
                </button>
                <?php if ($_SESSION['role'] === 'dentist'): ?>
                    <button type="button" class="settings-tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="profile">
                        <i class="fas fa-user-md mr-2"></i>Professional Profile
                    </button>
                <?php endif; ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <button type="button" class="settings-tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="users">
                        <i class="fas fa-users mr-2"></i>User Management
                    </button>
                <?php endif; ?>
                <button type="button" class="settings-tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="notifications">
                    <i class="fas fa-bell mr-2"></i>Notifications
                </button>
            </nav>
        </div>

        <!-- Clinic Information Tab -->
        <div id="clinic-tab" class="settings-tab-content p-6">
            <form method="POST" class="space-y-6" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_clinic">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="clinic_name" class="block text-sm font-medium text-gray-700 mb-1">Clinic Name</label>
                        <input type="text" id="clinic_name" name="clinic_name" value="<?= htmlspecialchars($settings['clinic_name'] ?? '') ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="clinic_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" id="clinic_phone" name="clinic_phone" value="<?= htmlspecialchars($settings['clinic_phone'] ?? '') ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="clinic_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="clinic_email" name="clinic_email" value="<?= htmlspecialchars($settings['clinic_email'] ?? '') ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="clinic_website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input type="url" id="clinic_website" name="clinic_website" value="<?= htmlspecialchars($settings['clinic_website'] ?? '') ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="clinic_logo_url" class="block text-sm font-medium text-gray-700 mb-1">Logo URL</label>
                        <input type="" id="clinic_logo_url" name="clinic_logo_url" value="<?= htmlspecialchars($settings['clinic_logo_url'] ?? '') ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <div class="mt-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ou téléchargez un logo</label>
                            <input type="file" name="clinic_logo_file" accept="image/*" class="block w-full text-sm text-gray-500">
                        </div>
                        <?php if (!empty($settings['clinic_logo_url'])): ?>
                            <div class="mt-2">
                                <img src=".<?= htmlspecialchars($settings['clinic_logo_url']) ?>" alt="Clinic Logo" class="h-16 rounded shadow border">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="clinic_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="clinic_description" name="clinic_description" rows="2"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($settings['clinic_description'] ?? '') ?></textarea>
                    </div>
                </div>
                <div>
                    <label for="clinic_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea id="clinic_address" name="clinic_address" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($settings['clinic_address'] ?? '') ?></textarea>
                </div>
                <!-- Working Hours -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Working Hours</h3>
                    <div class="space-y-4">
                        <?php
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        $day_names = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach ($days as $index => $day):
                            $day_hours = $working_hours[$day] ?? ['open' => '09:00', 'close' => '17:00', 'closed' => false];
                        ?>
                            <div class="flex items-center space-x-4">
                                <div class="w-24">
                                    <span class="text-sm font-medium text-gray-700"><?= $day_names[$index] ?></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="<?= $day ?>_closed" name="<?= $day ?>_closed" 
                                           <?= $day_hours['closed'] ? 'checked' : '' ?>
                                           class="day-closed-checkbox">
                                    <label for="<?= $day ?>_closed" class="text-sm text-gray-600">Closed</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="time" name="<?= $day ?>_open" value="<?= $day_hours['open'] ?>"
                                           class="border border-gray-300 rounded-md px-3 py-2 day-time-input"
                                           <?= $day_hours['closed'] ? 'disabled' : '' ?>>
                                    <span class="text-gray-500">to</span>
                                    <input type="time" name="<?= $day ?>_close" value="<?= $day_hours['close'] ?>"
                                           class="border border-gray-300 rounded-md px-3 py-2 day-time-input"
                                           <?= $day_hours['closed'] ? 'disabled' : '' ?>>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Professional Profile Tab (Dentist Only) -->
        <?php if ($_SESSION['role'] === 'dentist'): ?>
            <div id="profile-tab" class="settings-tab-content p-6 hidden">
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Professional Presentation</label>
                        <textarea name="presentation" rows="4" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Write a brief introduction about yourself and your practice..."
                        ><?= htmlspecialchars($settings['presentation'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certifications & Education</label>
                        <div id="certifications" class="space-y-3">
                            <?php 
                            $certifications = json_decode($settings['certifications'] ?? '[]', true) ?: [];
                            foreach ($certifications as $cert): 
                            ?>
                            <div class="flex items-center gap-2 certification-item">
                                <input type="text" name="certifications[]" value="<?= htmlspecialchars($cert) ?>" 
                                    class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <button type="button" class="remove-cert p-2 text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="add-cert" class="mt-3 inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Certification
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Languages Spoken</label>
                        <div id="languages" class="space-y-3">
                            <?php 
                            $languages = json_decode($settings['languages_spoken'] ?? '[]', true) ?: [];
                            foreach ($languages as $lang): 
                            ?>
                            <div class="flex items-center gap-2 language-item">
                                <input type="text" name="languages[]" placeholder="Language" 
                                    value="<?= htmlspecialchars($lang['language'] ?? '') ?>" 
                                    class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <select name="language_levels[]" 
                                    class="w-40 border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="native" <?= ($lang['level'] ?? '') === 'native' ? 'selected' : '' ?>>Native</option>
                                    <option value="fluent" <?= ($lang['level'] ?? '') === 'fluent' ? 'selected' : '' ?>>Fluent</option>
                                    <option value="intermediate" <?= ($lang['level'] ?? '') === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                                    <option value="basic" <?= ($lang['level'] ?? '') === 'basic' ? 'selected' : '' ?>>Basic</option>
                                </select>
                                <button type="button" class="remove-lang p-2 text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="add-lang" class="mt-3 inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Language
                        </button>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Save Profile
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- User Management Tab -->
        <div id="users-tab" class="settings-tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900">User Management</h3>
                <button id="addUserBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-user-plus mr-2"></i>Add User
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-semibold text-sm">
                                                <?php
                                                $firstInitial = isset($user['first_name']) && $user['first_name'] ? substr($user['first_name'], 0, 1) : '';
                                                $lastInitial = isset($user['last_name']) && $user['last_name'] ? substr($user['last_name'], 0, 1) : '';
                                                echo strtoupper($firstInitial . $lastInitial);
                                                ?>
                                            </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php
                                                $firstName = isset($user['first_name']) ? $user['first_name'] : '';
                                                $lastName = isset($user['last_name']) ? $user['last_name'] : '';
                                                echo htmlspecialchars(trim($firstName . ' ' . $lastName));
                                                ?>
                                            </div>
                                            <div class="text-sm text-gray-500"><?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php
                                        switch ($user['role']) {
                                            case 'admin':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            case 'dentist':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'assistant':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'receptionist':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                        }
                                        ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= !empty($user['last_login']) ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="edit-user-btn text-blue-600 hover:text-blue-900" data-id="<?= $user['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                            <button class="toggle-user-status-btn text-yellow-600 hover:text-yellow-900" data-id="<?= $user['id'] ?>">
                                                <i class="fas fa-<?= $user['status'] === 'active' ? 'pause' : 'play' ?>"></i>
                                            </button>
                                            <button class="delete-user-btn text-red-600 hover:text-red-900" data-id="<?= $user['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div id="notifications-tab" class="settings-tab-content p-6 hidden">
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <!-- Admin SMTP and SMS settings remain unchanged -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Global Email Settings</h4>
                    <?php 
                    $global_settings = fetchOne("SELECT * FROM global_settings LIMIT 1");
                    $smtp = json_decode($global_settings['smtp_settings'] ?? '{}', true);
                    ?>
                    <form method="POST" action="update_global_settings.php">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">SMTP Host</label>
                                <input type="text" name="smtp[host]" value="<?= htmlspecialchars($smtp['host'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">SMTP Port</label>
                                <input type="text" name="smtp[port]" value="<?= htmlspecialchars($smtp['port'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">SMTP Username</label>
                                <input type="text" name="smtp[username]" value="<?= htmlspecialchars($smtp['username'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">SMTP Password</label>
                                <input type="password" name="smtp[password]" value="<?= htmlspecialchars($smtp['password'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Encryption</label>
                                <select name="smtp[encryption]" class="w-full border rounded-md px-3 py-2">
                                    <option value="tls" <?= ($smtp['encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                    <option value="ssl" <?= ($smtp['encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Save SMTP Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- SMS Provider Settings (Admin Only) -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">SMS Provider Settings</h4>
                            <p class="text-sm text-gray-600">Configure SMS gateway settings for appointment reminders</p>
                        </div>
                    </div>
                    
                    <?php 
                    $sms_settings = json_decode($global_settings['sms_provider_settings'] ?? '{}', true);
                    $current_provider = $sms_settings['provider'] ?? 'twilio';
                    ?>

                    <form method="POST" action="update_sms_settings.php" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">SMS Provider</label>
                                <select name="sms[provider]" id="sms_provider" class="w-full border rounded-md px-3 py-2">
                                    <option value="twilio" <?= $current_provider === 'twilio' ? 'selected' : '' ?>>Twilio</option>
                                    <option value="vonage" <?= $current_provider === 'vonage' ? 'selected' : '' ?>>Vonage (Nexmo)</option>
                                    <option value="messagebird" <?= $current_provider === 'messagebird' ? 'selected' : '' ?>>MessageBird</option>
                                    <option value="clicksend" <?= $current_provider === 'clicksend' ? 'selected' : '' ?>>ClickSend</option>
                                </select>
                            </div>

                            <!-- Twilio Settings -->
                            <div class="provider-settings" id="twilio-settings">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Account SID</label>
                                        <input type="text" name="sms[twilio_account_sid]" 
                                            value="<?= htmlspecialchars($sms_settings['twilio_account_sid'] ?? '') ?>" 
                                            class="w-full border rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Auth Token</label>
                                        <input type="password" name="sms[twilio_auth_token]" 
                                            value="<?= htmlspecialchars($sms_settings['twilio_auth_token'] ?? '') ?>" 
                                            class="w-full border rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">From Number</label>
                                        <input type="text" name="sms[twilio_from_number]" 
                                            value="<?= htmlspecialchars($sms_settings['twilio_from_number'] ?? '') ?>" 
                                            class="w-full border rounded-md px-3 py-2"
                                            placeholder="+1234567890">
                                    </div>
                                </div>
                            </div>

                            <!-- Vonage Settings -->
                            <div class="provider-settings hidden" id="vonage-settings">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">API Key</label>
                                        <input type="text" name="sms[vonage_api_key]" 
                                            value="<?= htmlspecialchars($sms_settings['vonage_api_key'] ?? '') ?>" 
                                            class="w-full border rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">API Secret</label>
                                        <input type="password" name="sms[vonage_api_secret]" 
                                            value="<?= htmlspecialchars($sms_settings['vonage_api_secret'] ?? '') ?>" 
                                            class="w-full border rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">From Name/Number</label>
                                        <input type="text" name="sms[vonage_from]" 
                                            value="<?= htmlspecialchars($sms_settings['vonage_from'] ?? '') ?>" 
                                            class="w-full border rounded-md px-3 py-2">
                                    </div>
                                </div>
                            </div>

                            <!-- MessageBird Settings -->
                            <div class="provider-settings hidden" id="messagebird-settings">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">API Key</label>
                                        <input type="text" name="sms[messagebird_api_key]" 
                                            value="<?= htmlspecialchars($sms_settings['messagebird_api_key'] ?? '') ?>" 
                                            class="w-full border rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Originator</label>
                                        <input type="text" name="sms[messagebird_originator]" 
                                            value="<?= htmlspecialchars($sms_settings['messagebird_originator'] ?? '') ?>" 
                                            class="w-full border rounded-md px-3 py-2">
                                    </div>
                                </div>
                            </div>

                            <!-- ClickSend Settings -->
                            <div class="provider-settings hidden" id="clicksend-settings">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Username</label>
                                        <input type="text" name="sms[clicksend_username]" 
                                            value="<?= htmlspecialchars($sms_settings['clicksend_username'] ?? '') ?>" 
                                            class="w-full border rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">API Key</label>
                                        <input type="password" name="sms[clicksend_api_key]" 
                                            value="<?= htmlspecialchars($sms_settings['clicksend_api_key'] ?? '') ?>" 
                                            class="w-full border rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">From Number</label>
                                        <input type="text" name="sms[clicksend_from]" 
                                            value="<?= htmlspecialchars($sms_settings['clicksend_from'] ?? '') ?>" 
                                            class="w-full border rounded-md px-3 py-2">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Save SMS Settings
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Individual Notification Preferences (Both Admin & Dentist) -->
            <div class="bg-gray-50 rounded-lg p-6">
                <form method="POST" action="" class="space-y-6">
                    <input type="hidden" name="action" value="update_automation">
                    
                    <!-- Outgoing Notifications (Send to Patients) -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Préférences de communication patient</h4>
                        <p class="text-sm text-gray-600 mb-4">Choisissez comment vous souhaitez communiquer avec vos patients</p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                                <div>
                                    <label class="font-medium text-gray-900">Notifications par email</label>
                                    <p class="text-sm text-gray-500">Envoyer les confirmations et rappels de rendez-vous par email</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notifications[send_email]" class="sr-only peer" 
                                        <?= ($automation_settings['send_email_enabled'] ?? false) ? 'checked' : '' ?>>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                                <div>
                                    <label class="font-medium text-gray-900">Notifications par SMS</label>
                                    <p class="text-sm text-gray-500">Envoyer les rappels de rendez-vous par SMS</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notifications[send_sms]" class="sr-only peer"
                                        <?= ($automation_settings['send_sms_enabled'] ?? false) ? 'checked' : '' ?>>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                                <div>
                                    <label class="font-medium text-gray-900">Notifications WhatsApp</label>
                                    <p class="text-sm text-gray-500">Envoyer les rappels de rendez-vous via WhatsApp</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notifications[send_whatsapp]" class="sr-only peer"
                                        <?= ($automation_settings['send_whatsapp_enabled'] ?? false) ? 'checked' : '' ?>>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Incoming Notifications (Receive as Dentist) -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Vos préférences de notification</h4>
                        <p class="text-sm text-gray-600 mb-4">Choisissez comment vous souhaitez recevoir les notifications</p>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                                <div>
                                    <label class="font-medium text-gray-900">Recevoir les notifications par email</label>
                                    <p class="text-sm text-gray-500">Recevez une notification pour les nouveaux rendez-vous, annulations et mises à jour</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notifications[receive_email]" class="sr-only peer"
                                        <?= ($automation_settings['receive_email_enabled'] ?? false) ? 'checked' : '' ?>>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                                <div>
                                    <label class="font-medium text-gray-900">Recevoir les notifications par SMS</label>
                                    <p class="text-sm text-gray-500">Recevez des alertes SMS pour les mises à jour urgentes et les changements</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notifications[receive_sms]" class="sr-only peer"
                                        <?= ($automation_settings['receive_sms_enabled'] ?? false) ? 'checked' : '' ?>>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                                <div>
                                    <label class="font-medium text-gray-900">Recevoir les notifications WhatsApp</label>
                                    <p class="text-sm text-gray-500">Recevez des messages WhatsApp instantanés pour les mises à jour importantes</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notifications[receive_whatsapp]" class="sr-only peer"
                                        <?= ($automation_settings['receive_whatsapp_enabled'] ?? false) ? 'checked' : '' ?>>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Enregistrer les préférences de notification
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching
        const tabButtons = document.querySelectorAll('.settings-tab-btn');
        const tabContents = document.querySelectorAll('.settings-tab-content');

        // Always show only the first tab by default
        tabContents.forEach((content, idx) => {
            if (idx === 0) {
                content.classList.remove('hidden');
            } else {
                content.classList.add('hidden');
            }
        });
        tabButtons.forEach((btn, idx) => {
            if (idx === 0) {
                btn.classList.add('border-blue-500', 'text-blue-600');
            } else {
                btn.classList.remove('border-blue-500', 'text-blue-600');
            }
        });

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                // Remove active classes
                tabButtons.forEach(btn => btn.classList.remove('border-blue-500', 'text-blue-600'));
                tabContents.forEach(content => content.classList.add('hidden'));
                // Add active classes
                button.classList.add('border-blue-500', 'text-blue-600');
                const tabContent = document.getElementById(`${tabId}-tab`);
                if (tabContent) tabContent.classList.remove('hidden');
            });
        });

        // Add user button
        document.getElementById('addUserBtn').addEventListener('click', () => {
            // Open the add user modal (you need to implement this)
            openAddUserModal();
        });

        // Add certification button
        const addCertBtn = document.getElementById('add-cert');
        addCertBtn.addEventListener('click', () => {
            const container = document.getElementById('certifications');
            const newCert = document.createElement('div');
            newCert.className = 'flex items-center gap-2 certification-item';
            newCert.innerHTML = `
                <input type="text" name="certifications[]" placeholder="Enter certification" 
                    class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="button" class="remove-cert p-2 text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(newCert);
        });

        // Add language button
        const addLangBtn = document.getElementById('add-lang');
        addLangBtn.addEventListener('click', () => {
            const container = document.getElementById('languages');
            const newLang = document.createElement('div');
            newLang.className = 'flex items-center gap-2 language-item';
            newLang.innerHTML = `
                <input type="text" name="languages[]" placeholder="Enter language" 
                    class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                <select name="language_levels[]" 
                    class="w-40 border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="native">Native</option>
                    <option value="fluent">Fluent</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="basic">Basic</option>
                </select>
                <button type="button" class="remove-lang p-2 text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(newLang);
        });

        // Remove buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.remove-cert')) {
                e.target.closest('.certification-item').remove();
            }
            if (e.target.closest('.remove-lang')) {
                e.target.closest('.language-item').remove();
            }
        });

        // SMS Provider Settings
        const smsProvider = document.getElementById('sms_provider');
        const providerSettings = document.querySelectorAll('.provider-settings');

        function showSelectedProviderSettings() {
            const selectedProvider = smsProvider.value;
            providerSettings.forEach(settings => {
                settings.classList.add('hidden');
            });
            document.getElementById(`${selectedProvider}-settings`).classList.remove('hidden');
        }

        smsProvider.addEventListener('change', showSelectedProviderSettings);
        showSelectedProviderSettings(); // Show initial selection
    });

    // Add save notification preferences functionality
    document.querySelectorAll('input[type="checkbox"][name^="notifications"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const form = this.closest('form');
            const formData = new FormData(form);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // If the response is JSON, handle it, else ignore (for normal POST)
                try { return response.json(); } catch { return {}; }
            })
            .then(data => {
                if (data && data.success) {
                    // Show success message if needed
                }
            });
        });
    });
    </script>