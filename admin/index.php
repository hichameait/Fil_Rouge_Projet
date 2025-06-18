<?php
require_once '../dashboard/config/database.php';

// Check if session is not already active before starting it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Fetch overview stats with default values for null results
$stats = [
    'total_dentists' => (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'dentist'")->fetchColumn() ?: 0,
    'active_subscriptions' => (int)$pdo->query("SELECT COUNT(*) FROM subscriptions WHERE status = 'active'")->fetchColumn() ?: 0,
    'total_revenue' => (float)$pdo->query("
        SELECT COALESCE(SUM(price), 0) 
        FROM subscriptions
    ")->fetchColumn() ?: 0.00,
];

// Fetch recent payments
$recent_payments = $pdo->query("
    SELECT i.invoice_number, u.first_name, u.last_name, i.total_amount, i.paid_date 
    FROM invoices i 
    JOIN users u ON i.user_id = u.id 
    WHERE i.status = 'paid' 
    ORDER BY i.paid_date DESC 
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SmileDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="ml-64 flex-1 p-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500 text-sm">Total Dentistes</h3>
                            <p class="text-2xl font-semibold"><?= number_format($stats['total_dentists']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500 text-sm">Abonnements Actifs</h3>
                            <p class="text-2xl font-semibold"><?= number_format($stats['active_subscriptions']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-gray-500 text-sm">Revenue Total</h3>
                            <p class="text-2xl font-semibold">
                                <?= number_format($stats['total_revenue'], 0, '', ' ') ?> MAD
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Paiements Récents</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facture</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dentiste</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($recent_payments as $payment): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($payment['invoice_number']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= number_format($payment['total_amount'], 0, '', ' ') ?> MAD
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($payment['paid_date'])) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
