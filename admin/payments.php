<?php
require_once '../dashboard/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php');
    exit;
}

// Fetch payments (paid invoices)
$stmt = $pdo->query("
    SELECT 
        i.id,
        i.invoice_number,
        i.total_amount,
        i.payment_method,
        i.paid_date,
        u.first_name,
        u.last_name,
        u.email
    FROM invoices i
    JOIN users u ON i.user_id = u.id
    WHERE i.status = 'paid'
    ORDER BY i.paid_date DESC
");
$payments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiements | SmileDesk Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <main class="ml-64 flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Historique des Paiements</h1>
            </div>
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dentiste</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Méthode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Paiement</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($payment['invoice_number']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($payment['email']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= number_format($payment['total_amount'], 2, ',', ' ') ?> €</td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($payment['payment_method'] ?? '-') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $payment['paid_date'] ? date('d/m/Y H:i', strtotime($payment['paid_date'])) : '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucun paiement trouvé.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
