<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <?php
                $page_titles = [
                    'dashboard' => 'Tableau de bord',
                    'patients' => 'Gestion des patients',
                    'appointments' => 'Gestion des rendez-vous',
                    'invoices' => 'Gestion des factures',
                    'analytics' => 'Analyses & Rapports',
                    'documents' => 'Gestion des documents',
                    'services' => 'Catalogue des services',
                    'settings' => 'Paramètres'
                ];
                echo $page_titles[$current_page] ?? 'SmileDesk Tableau de bord';
                ?>
            </h1>
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <!-- <div class="relative">
                <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </button>
            </div> -->
            
            <!-- New Appointment Button -->
            <button id="newAppointmentBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nouveau rendez-vous
            </button>
        </div>
    </div>
</header>
