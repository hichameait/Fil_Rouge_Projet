<?php
require_once '../dashboard/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch settings for this admin (if any)
$settings = $pdo->query("SELECT * FROM settings WHERE user_id = $user_id")->fetch(PDO::FETCH_ASSOC);

// Fetch global SMTP and SMS settings
$global_settings = $pdo->query("SELECT * FROM global_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$smtp = json_decode($global_settings['smtp_settings'] ?? '{}', true);
$sms_settings = json_decode($global_settings['sms_provider_settings'] ?? '{}', true);

$success_message = $error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update SMTP settings
    if (isset($_POST['action']) && $_POST['action'] === 'update_smtp') {
        try {
            $smtp_settings = json_encode([
                'host' => $_POST['smtp_host'] ?? '',
                'port' => $_POST['smtp_port'] ?? '',
                'username' => $_POST['smtp_username'] ?? '',
                'password' => $_POST['smtp_password'] ?? '',
                'encryption' => $_POST['smtp_encryption'] ?? 'tls'
            ]);
            $stmt = $pdo->prepare("
                INSERT INTO global_settings (smtp_settings) VALUES (?)
                ON DUPLICATE KEY UPDATE smtp_settings = VALUES(smtp_settings)
            ");
            $stmt->execute([$smtp_settings]);
            $success_message = "SMTP settings updated successfully!";
            $global_settings = $pdo->query("SELECT * FROM global_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            $smtp = json_decode($global_settings['smtp_settings'] ?? '{}', true);
        } catch (Exception $e) {
            $error_message = "Error updating SMTP settings: " . $e->getMessage();
        }
    }
    // Update SMS provider settings
    if (isset($_POST['action']) && $_POST['action'] === 'update_sms') {
        try {
            $sms = $_POST['sms'] ?? [];
            $provider = $sms['provider'] ?? 'twilio';
            $sms_settings = ['provider' => $provider];
            // Save only the relevant fields for the selected provider
            if ($provider === 'twilio') {
                $sms_settings['twilio_account_sid'] = $sms['twilio_account_sid'] ?? '';
                $sms_settings['twilio_auth_token'] = $sms['twilio_auth_token'] ?? '';
                $sms_settings['twilio_from_number'] = $sms['twilio_from_number'] ?? '';
            } elseif ($provider === 'vonage') {
                $sms_settings['vonage_api_key'] = $sms['vonage_api_key'] ?? '';
                $sms_settings['vonage_api_secret'] = $sms['vonage_api_secret'] ?? '';
                $sms_settings['vonage_from'] = $sms['vonage_from'] ?? '';
            } elseif ($provider === 'messagebird') {
                $sms_settings['messagebird_api_key'] = $sms['messagebird_api_key'] ?? '';
                $sms_settings['messagebird_originator'] = $sms['messagebird_originator'] ?? '';
            } elseif ($provider === 'clicksend') {
                $sms_settings['clicksend_username'] = $sms['clicksend_username'] ?? '';
                $sms_settings['clicksend_api_key'] = $sms['clicksend_api_key'] ?? '';
                $sms_settings['clicksend_from'] = $sms['clicksend_from'] ?? '';
            }
            $stmt = $pdo->prepare("
                INSERT INTO global_settings (sms_provider_settings) VALUES (?)
                ON DUPLICATE KEY UPDATE sms_provider_settings = VALUES(sms_provider_settings)
            ");
            $stmt->execute([json_encode($sms_settings)]);
            $success_message = "SMS provider settings updated successfully!";
            $global_settings = $pdo->query("SELECT * FROM global_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            $sms_settings = json_decode($global_settings['sms_provider_settings'] ?? '{}', true);
        } catch (Exception $e) {
            $error_message = "Error updating SMS provider settings: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres | SmileDesk Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <main class="ml-64 flex-1 p-8">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-2xl font-semibold text-gray-900 mb-6">Paramètres administrateur</h1>
                <?php if ($success_message): ?>
                    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4"><?= $success_message ?></div>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-4"><?= $error_message ?></div>
                <?php endif; ?>

                

                <!-- SMTP Settings -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-bold mb-4 text-gray-800">Paramètres SMTP (Email)</h2>
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="update_smtp">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Hôte SMTP</label>
                                <input type="text" name="smtp_host" value="<?= htmlspecialchars($smtp['host'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Port SMTP</label>
                                <input type="text" name="smtp_port" value="<?= htmlspecialchars($smtp['port'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Nom d'utilisateur SMTP</label>
                                <input type="text" name="smtp_username" value="<?= htmlspecialchars($smtp['username'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Mot de passe SMTP</label>
                                <input type="password" name="smtp_password" value="<?= htmlspecialchars($smtp['password'] ?? '') ?>" class="w-full border rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Chiffrement</label>
                                <select name="smtp_encryption" class="w-full border rounded-md px-3 py-2">
                                    <option value="tls" <?= ($smtp['encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                    <option value="ssl" <?= ($smtp['encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Enregistrer SMTP
                            </button>
                        </div>
                    </form>
                </div>

                <!-- SMS Provider Settings (Admin Only) -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">SMS Provider Settings</h4>
                            <p class="text-sm text-gray-600">Configure SMS gateway settings for appointment reminders</p>
                        </div>
                    </div>
                    <form method="POST" class="space-y-6">
                        <input type="hidden" name="action" value="update_sms">
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-1">SMS Provider</label>
                            <select name="sms[provider]" id="sms_provider" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                <option value="twilio" <?= ($sms_settings['provider'] ?? 'twilio') === 'twilio' ? 'selected' : '' ?>>Twilio</option>
                                <option value="vonage" <?= ($sms_settings['provider'] ?? '') === 'vonage' ? 'selected' : '' ?>>Vonage (Nexmo)</option>
                                <option value="messagebird" <?= ($sms_settings['provider'] ?? '') === 'messagebird' ? 'selected' : '' ?>>MessageBird</option>
                                <option value="clicksend" <?= ($sms_settings['provider'] ?? '') === 'clicksend' ? 'selected' : '' ?>>ClickSend</option>
                            </select>
                        </div>
                        <!-- Twilio Settings -->
                        <div class="provider-settings <?= ($sms_settings['provider'] ?? 'twilio') !== 'twilio' ? 'hidden' : '' ?> border rounded-lg p-4 mb-4 bg-blue-50" id="twilio-settings">
                            <h5 class="font-semibold text-blue-700 mb-2 flex items-center"><i class="fab fa-twilio mr-2"></i>Twilio</h5>
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
                        <div class="provider-settings <?= ($sms_settings['provider'] ?? '') !== 'vonage' ? 'hidden' : '' ?> border rounded-lg p-4 mb-4 bg-yellow-50" id="vonage-settings">
                            <h5 class="font-semibold text-yellow-700 mb-2 flex items-center"><i class="fab fa-vuejs mr-2"></i>Vonage (Nexmo)</h5>
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
                        <div class="provider-settings <?= ($sms_settings['provider'] ?? '') !== 'messagebird' ? 'hidden' : '' ?> border rounded-lg p-4 mb-4 bg-purple-50" id="messagebird-settings">
                            <h5 class="font-semibold text-purple-700 mb-2 flex items-center"><i class="fas fa-dove mr-2"></i>MessageBird</h5>
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
                        <div class="provider-settings <?= ($sms_settings['provider'] ?? '') !== 'clicksend' ? 'hidden' : '' ?> border rounded-lg p-4 mb-4 bg-green-50" id="clicksend-settings">
                            <h5 class="font-semibold text-green-700 mb-2 flex items-center"><i class="fas fa-paper-plane mr-2"></i>ClickSend</h5>
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
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Save SMS Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <script>
    // Show/hide SMS provider settings
    document.addEventListener('DOMContentLoaded', function() {
        const providerSelect = document.getElementById('sms_provider');
        const providers = ['twilio', 'vonage', 'messagebird', 'clicksend'];
        function showProviderSettings() {
            providers.forEach(function(p) {
                document.getElementById(p + '-settings').classList.add('hidden');
            });
            const selected = providerSelect.value;
            document.getElementById(selected + '-settings').classList.remove('hidden');
        }
        providerSelect.addEventListener('change', showProviderSettings);
        showProviderSettings();
    });
    </script>
</body>
</html>