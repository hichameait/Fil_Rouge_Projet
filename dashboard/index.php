<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

// Check authentication
if (!isLoggedIn()) {
    header('Location: ../auth/login.php');
    exit;
}

$current_page = $_GET['page'] ?? 'dashboard';
$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileDesk - Dental Practice Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/custom.css">
    <!-- Add these meta tags -->
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
    <meta name="api-base" content="/pfa/api">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include __DIR__ . '/includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <?php include __DIR__ . '/includes/header.php'; ?>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <?php
                switch ($current_page) {
                    case 'patients':
                        include __DIR__ . '/pages/patients.php';
                        break;
                    case 'appointments':
                        include __DIR__ . '/pages/appointments.php';
                        break;
                    case 'invoices':
                        include __DIR__ . '/pages/invoices.php';
                        break;
                    case 'analytics':
                        include __DIR__ . '/pages/analytics.php';
                        break;
                    case 'documents':
                        include __DIR__ . '/pages/documents.php';
                        break;
                    case 'services':
                        include __DIR__ . '/pages/services.php';
                        break;
                    case 'settings':
                        include __DIR__ . '/pages/settings.php';
                        break;
                    default:
                        include __DIR__ . '/pages/dashboard.php';
                        break;
                }
                ?>
            </main>
        </div>
    </div>

    <!-- Modals -->
    <?php include __DIR__ . '/includes/modals.php'; ?>

    <!-- Scripts -->
    <script src="assets/js/utils.js"></script>
    <script src="assets/js/formHandlers.js"></script>
    <script src="assets/js/eventHandlers.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/charts.js"></script>
    <script src="assets/js/appointments.js"></script>
</body>
</html>
