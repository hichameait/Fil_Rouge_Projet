<?php
requireRole('admin');

$user_id = $_SESSION['user_id'];

// Get settings information
$settings = fetchOne(
    "SELECT * FROM settings WHERE user_id = ?",
    [$user_id]
);

$users = fetchAll(
    "SELECT * FROM users WHERE id = ? ORDER BY role, first_name",
    [$user_id]
);

$success_message = $error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'update_settings':
            updateSettings();
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
    }
}

function updateSettings() {
    global $pdo, $user_id, $success_message, $error_message;
    try {
        $stmt = $pdo->prepare("
            UPDATE settings SET 
                clinic_name = ?, clinic_address = ?, clinic_phone = ?, clinic_email = ?, clinic_website = ?,
                clinic_logo_url = ?, clinic_description = ?, working_hours = ?, updated_at = NOW()
            WHERE user_id = ?
        ");
        $working_hours = json_encode([
            'monday' => ['open' => $_POST['monday_open'], 'close' => $_POST['monday_close'], 'closed' => isset($_POST['monday_closed'])],
            'tuesday' => ['open' => $_POST['tuesday_open'], 'close' => $_POST['tuesday_close'], 'closed' => isset($_POST['tuesday_closed'])],
            'wednesday' => ['open' => $_POST['wednesday_open'], 'close' => $_POST['wednesday_close'], 'closed' => isset($_POST['wednesday_closed'])],
            'thursday' => ['open' => $_POST['thursday_open'], 'close' => $_POST['thursday_close'], 'closed' => isset($_POST['thursday_closed'])],
            'friday' => ['open' => $_POST['friday_open'], 'close' => $_POST['friday_close'], 'closed' => isset($_POST['friday_closed'])],
            'saturday' => ['open' => $_POST['saturday_open'], 'close' => $_POST['saturday_close'], 'closed' => isset($_POST['saturday_closed'])],
            'sunday' => ['open' => $_POST['sunday_open'], 'close' => $_POST['sunday_close'], 'closed' => isset($_POST['sunday_closed'])]
        ]);
        $stmt->execute([
            $_POST['clinic_name'],
            $_POST['clinic_address'],
            $_POST['clinic_phone'],
            $_POST['clinic_email'],
            $_POST['clinic_website'],
            $_POST['clinic_logo_url'] ?? '',
            $_POST['clinic_description'] ?? '',
            $working_hours,
            $user_id
        ]);
        $success_message = "Settings updated successfully!";
    } catch (Exception $e) {
        $error_message = "Error updating settings: " . $e->getMessage();
    }
}

// Parse working hours
$working_hours = json_decode($settings['working_hours'] ?? '{}', true);
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
                <button type="button" class="settings-tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="users">
                    <i class="fas fa-users mr-2"></i>User Management
                </button>
                <button type="button" class="settings-tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="automation">
                    <i class="fas fa-robot mr-2"></i>Automation
                </button>
                <button type="button" class="settings-tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-tab="notifications">
                    <i class="fas fa-bell mr-2"></i>Notifications
                </button>
            </nav>
        </div>

        <!-- Clinic Information Tab -->
        <div id="clinic-tab" class="settings-tab-content p-6">
            <form method="POST" class="space-y-6">
                <input type="hidden" name="action" value="update_settings">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="clinic_name" class="block text-sm font-medium text-gray-700 mb-1">Clinic Name</label>
                        <input type="text" id="clinic_name" name="clinic_name" value="<?= htmlspecialchars($settings['clinic_name']) ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="clinic_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" id="clinic_phone" name="clinic_phone" value="<?= htmlspecialchars($settings['clinic_phone']) ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="clinic_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="clinic_email" name="clinic_email" value="<?= htmlspecialchars($settings['clinic_email']) ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="clinic_website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input type="url" id="clinic_website" name="clinic_website" value="<?= htmlspecialchars($settings['clinic_website'] ?? '') ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="clinic_logo_url" class="block text-sm font-medium text-gray-700 mb-1">Logo URL</label>
                        <input type="url" id="clinic_logo_url" name="clinic_logo_url" value="<?= htmlspecialchars($settings['clinic_logo_url'] ?? '') ?>"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($settings['clinic_address']) ?></textarea>
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
                                                <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= $user['first_name'] . ' ' . $user['last_name'] ?>
                                            </div>
                                            <div class="text-sm text-gray-500"><?= $user['email'] ?></div>
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
                                    <?= $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never' ?>
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

        <!-- Automation Tab -->
        <div id="automation-tab" class="settings-tab-content p-6 hidden">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Automation Settings</h3>
            <div class="space-y-6">
                <!-- SMS Reminders -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">SMS Appointment Reminders</h4>
                            <p class="text-sm text-gray-600">Automatically send SMS reminders to patients</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" id="sms_reminders_enabled">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reminder Time</label>
                            <select class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="24">24 hours before</option>
                                <option value="12">12 hours before</option>
                                <option value="6">6 hours before</option>
                                <option value="2">2 hours before</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SMS Provider</label>
                            <select class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="twilio">Twilio</option>
                                <option value="nexmo">Nexmo</option>
                                <option value="custom">Custom Provider</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sender Name</label>
                            <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Clinic Name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                            <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="API Key">
                        </div>
                    </div>
                </div>
                <!-- Email Notifications -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Email Notifications</h4>
                            <p class="text-sm text-gray-600">Send email notifications for various events</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" id="email_notifications_enabled">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">New appointment confirmations</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Appointment reminders</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Payment receipts</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Treatment summaries</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Custom email template</span>
                        </label>
                    </div>
                </div>
                <!-- Chatbot Integration -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Chatbot Integration</h4>
                            <p class="text-sm text-gray-600">AI-powered chatbot for patient inquiries</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" id="chatbot_enabled">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div class="text-sm text-gray-600">
                        <p>Enable AI chatbot to handle common patient questions about:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Appointment scheduling</li>
                            <li>Office hours and location</li>
                            <li>Services and pricing</li>
                            <li>Pre-appointment instructions</li>
                            <li>Insurance and billing</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div id="notifications-tab" class="settings-tab-content p-6 hidden">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Notification Preferences</h3>
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">System Notifications</h4>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">New patient registrations</span>
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Appointment cancellations</span>
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Payment received</span>
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Overdue invoices</span>
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Custom system alert</span>
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Daily Summary</h4>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Daily appointment summary</span>
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Revenue summary</span>
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Patient no-shows</span>
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Custom summary</span>
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching logic
document.querySelectorAll('.settings-tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.settings-tab-btn').forEach(b => b.classList.remove('border-blue-500', 'text-blue-600'));
        this.classList.add('border-blue-500', 'text-blue-600');
        document.querySelectorAll('.settings-tab-content').forEach(tab => tab.classList.add('hidden'));
        const tabId = this.getAttribute('data-tab');
        if (tabId === 'clinic') document.getElementById('clinic-tab').classList.remove('hidden');
        if (tabId === 'users') document.getElementById('users-tab').classList.remove('hidden');
        if (tabId === 'automation') document.getElementById('automation-tab').classList.remove('hidden');
        if (tabId === 'notifications') document.getElementById('notifications-tab').classList.remove('hidden');
    });
});

// Enable/disable time inputs based on "Closed" checkbox
document.querySelectorAll('.day-closed-checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        const parent = this.closest('.flex.items-center');
        if (!parent) return;
        const timeInputs = parent.querySelectorAll('.day-time-input');
        timeInputs.forEach(input => input.disabled = this.checked);
    });
});
</script>
