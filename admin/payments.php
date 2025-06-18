<?php
require_once '../dashboard/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Fetch only subscriptions (Stripe/online payments)
$stmt = $pdo->query("
    SELECT 
        s.id,
        sp.name AS plan_name,
        sp.price AS total_amount,
        s.payment_method,
        s.start_date AS paid_date,
        u.first_name,
        u.last_name,
        u.email,
        s.status AS subscription_status
    FROM subscriptions s
    JOIN users u ON s.user_id = u.id
    JOIN subscription_plans sp ON s.plan_id = sp.id
    ORDER BY s.created_at DESC
");
$subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <h1 class="text-2xl font-semibold text-gray-900">Historique des Abonnements</h1>
            </div>
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Abonnement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dentiste</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Méthode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Paiement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($subscriptions as $sub): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold"><?= htmlspecialchars($sub['plan_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($sub['first_name'] . ' ' . $sub['last_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($sub['email']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= number_format($sub['total_amount'], 0, '', ' ') ?> MAD</td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($sub['payment_method'] ?? '-') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $sub['paid_date'] ? date('d/m/Y H:i', strtotime($sub['paid_date'])) : '-' ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $color = $sub['subscription_status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                                    echo '<span class="px-2 py-1 text-xs rounded ' . $color . '">' . ucfirst($sub['subscription_status']) . '</span>';
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($subscriptions)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Aucun abonnement trouvé.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
