<aside class="w-64 bg-white border-r border-gray-200 fixed h-full">
    <div class="flex items-center justify-between h-16 border-b px-6">
        <span class="text-xl font-semibold text-blue-600"><img src="./logo/logo.png" alt="Logo" class="h-10 w-auto"></span>
    </div>
    <nav class="mt-6">
        <a href="./index.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 <?= strpos($_SERVER['PHP_SELF'], 'dashboard.php') ? 'bg-blue-50 text-blue-600' : '' ?>">
            <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
            <span>Dashboard</span>
        </a>
        <a href="./dentists.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 <?= strpos($_SERVER['PHP_SELF'], 'dentists.php') ? 'bg-blue-50 text-blue-600' : '' ?>">
            <i class="fas fa-user-md w-5 h-5 mr-3"></i>
            <span>Dentistes</span>
        </a>
        <a href="./plans.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 <?= strpos($_SERVER['PHP_SELF'], 'plans.php') ? 'bg-blue-50 text-blue-600' : '' ?>">
            <i class="fas fa-box w-5 h-5 mr-3"></i>
            <span>Plans</span>
        </a>
        <a href="./payments.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 <?= strpos($_SERVER['PHP_SELF'], 'payments.php') ? 'bg-blue-50 text-blue-600' : '' ?>">
            <i class="fas fa-credit-card w-5 h-5 mr-3"></i>
            <span>Paiements</span>
        </a>
        <a href="./settings.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 <?= strpos($_SERVER['PHP_SELF'], 'settings.php') ? 'bg-blue-50 text-blue-600' : '' ?>">
            <i class="fas fa-cog w-5 h-5 mr-3"></i>
            <span>Paramètres</span>
        </a>
        <hr class="my-4 border-gray-200">
        <a href="../auth/logout.php" class="flex items-center px-6 py-3 text-red-600 hover:bg-red-50">
            <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
            <span>Déconnexion</span>
        </a>
    </nav>
</aside>
