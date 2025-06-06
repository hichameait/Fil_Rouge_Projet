<aside class="hidden md:flex h-screen w-64 flex-col border-r bg-white">
    <div class="p-6 border-b">
        <h2 class="text-lg font-semibold">DentalCare</h2>
    </div>
    <div class="flex-1 overflow-auto py-2">
        <nav class="grid gap-1 px-2">
            <a href="index.php" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all hover:bg-gray-100 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-gray-100 text-blue-600' : 'text-gray-500'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            <a href="patients.php" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all hover:bg-gray-100 <?php echo basename($_SERVER['PHP_SELF']) == 'patients.php' ? 'bg-gray-100 text-blue-600' : 'text-gray-500'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Patients
            </a>
            <a href="appointments.php" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all hover:bg-gray-100 <?php echo basename($_SERVER['PHP_SELF']) == 'appointments.php' ? 'bg-gray-100 text-blue-600' : 'text-gray-500'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Appointments
            </a>
            <a href="invoices.php" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all hover:bg-gray-100 <?php echo basename($_SERVER['PHP_SELF']) == 'invoices.php' ? 'bg-gray-100 text-blue-600' : 'text-gray-500'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Invoices
            </a>
            <a href="analytics.php" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all hover:bg-gray-100 <?php echo basename($_SERVER['PHP_SELF']) == 'analytics.php' ? 'bg-gray-100 text-blue-600' : 'text-gray-500'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Analytics
            </a>
            <a href="documents.php" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all hover:bg-gray-100 <?php echo basename($_SERVER['PHP_SELF']) == 'documents.php' ? 'bg-gray-100 text-blue-600' : 'text-gray-500'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Documents
            </a>
            <a href="settings.php" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all hover:bg-gray-100 <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'bg-gray-100 text-blue-600' : 'text-gray-500'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
            </a>
        </nav>
    </div>
    <div class="mt-auto p-4 border-t">
        <a href="../index.php" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-500 transition-all hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Log out
        </a>
    </div>
</aside>

<!-- Mobile Sidebar Toggle -->
<div class="md:hidden fixed left-4 top-4 z-40">
    <button id="sidebar-toggle" class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>

<!-- Mobile Sidebar -->
<div id="mobile-sidebar" class="md:hidden fixed inset-0 z-30 bg-gray-600 bg-opacity-75 hidden">
    <div class="fixed inset-y-0 left-0 w-64 bg-white">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold">DentalCare</h2>
            <button id="sidebar-close" class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-auto py-2">
            <nav class="grid gap-1 px-2">
                <a href="index.php" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all hover:bg-gray-100 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-gray-100 text-blue-600' : 'text-gray-500'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <!-- Repeat other menu items here -->
            </nav>
        </div>
        <div class="mt-auto p-4 border-t">
            <a href="../index.php" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-500 transition-all hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Log out
            </a>
        </div>
    </div>
</div>

<script>
    // Mobile sidebar toggle
    document.getElementById('sidebar-toggle').addEventListener('click', function() {
        document.getElementById('mobile-sidebar').classList.remove('hidden');
    });
    
    document.getElementById('sidebar-close').addEventListener('click', function() {
        document.getElementById('mobile-sidebar').classList.add('hidden');
    });
</script>