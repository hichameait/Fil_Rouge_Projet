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

// Fetch dentists with their subscription info
$stmt = $pdo->query("
    SELECT 
        u.id,
        u.first_name,
        u.last_name,
        u.email,
        u.status,
        s.clinic_name,
        sp.name as plan_name
    FROM users u
    LEFT JOIN settings s ON u.id = s.user_id
    LEFT JOIN subscriptions sub ON u.id = sub.user_id AND sub.status = 'active'
    LEFT JOIN subscription_plans sp ON sub.plan_id = sp.id
    WHERE u.role = 'dentist'
    ORDER BY u.created_at DESC
");
$dentists = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Dentistes | SmileDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="ml-64 flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Gestion des Dentistes</h1>
                <button onclick="openDentistModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Ajouter un Dentiste
                </button>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="flex gap-4">
                    <input type="text" placeholder="Rechercher..." class="flex-1 border rounded-lg px-4 py-2">
                    <select class="border rounded-lg px-4 py-2">
                        <option value="">Tous les plans</option>
                        <option value="basic">Basic</option>
                        <option value="pro">Pro</option>
                    </select>
                    <select class="border rounded-lg px-4 py-2">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
            </div>

            <!-- Dentists Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cabinet</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($dentists as $dentist): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= htmlspecialchars($dentist['first_name'] . ' ' . $dentist['last_name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= htmlspecialchars($dentist['email']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= htmlspecialchars($dentist['clinic_name'] ?? '-') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-sm rounded-full <?= $dentist['plan_name'] ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' ?>">
                                    <?= htmlspecialchars($dentist['plan_name'] ?? 'Aucun plan') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-sm rounded-full <?= $dentist['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $dentist['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="viewDentist(<?= $dentist['id'] ?>)" class="text-blue-600 hover:text-blue-900 mr-3" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editDentist(<?= $dentist['id'] ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteDentist(<?= $dentist['id'] ?>)" class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Dentist Modal (Add/Edit) -->
    <div id="dentistModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <form id="dentistForm" class="space-y-4">
                <input type="hidden" id="dentistId" name="id">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prénom</label>
                    <input type="text" id="dentistFirstName" name="first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" id="dentistLastName" name="last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="dentistEmail" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Statut</label>
                    <select id="dentistStatus" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDentistModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Dentist Modal -->
    <div id="viewDentistModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div id="viewDentistContent"></div>
            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeViewDentistModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md">Fermer</button>
            </div>
        </div>
    </div>

    <script>
        function openDentistModal(data = null) {
            const modal = document.getElementById('dentistModal');
            const form = document.getElementById('dentistForm');
            form.reset();
            document.getElementById('dentistId').value = '';
            if (data) {
                document.getElementById('dentistId').value = data.id;
                document.getElementById('dentistFirstName').value = data.first_name;
                document.getElementById('dentistLastName').value = data.last_name;
                document.getElementById('dentistEmail').value = data.email;
                document.getElementById('dentistStatus').value = data.status;
            }
            modal.classList.remove('hidden');
        }
        function closeDentistModal() {
            document.getElementById('dentistModal').classList.add('hidden');
        }
        document.getElementById('dentistForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('action', formData.get('id') ? 'update' : 'create');
            try {
                const response = await fetch('./api/dentists.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    throw new Error(result.error);
                }
            } catch (error) {
                alert('Erreur: ' + error.message);
            }
        });
        async function editDentist(id) {
            try {
                const response = await fetch(`./api/dentists.php?action=get&id=${id}`);
                const data = await response.json();
                openDentistModal(data);
            } catch (error) {
                alert('Erreur lors du chargement du dentiste');
            }
        }
        async function viewDentist(id) {
            try {
                const response = await fetch(`./api/dentists.php?action=get&id=${id}`);
                const d = await response.json();
                let html = `<h2 class="text-xl font-bold mb-2">${d.first_name} ${d.last_name}</h2>
                    <p class="mb-2"><strong>Email:</strong> ${d.email}</p>
                    <p class="mb-2"><strong>Statut:</strong> ${d.status == 'active' ? '<span class="text-green-600">Actif</span>' : '<span class="text-red-600">Inactif</span>'}</p>`;
                document.getElementById('viewDentistContent').innerHTML = html;
                document.getElementById('viewDentistModal').classList.remove('hidden');
            } catch (error) {
                alert('Erreur lors du chargement du dentiste');
            }
        }
        function closeViewDentistModal() {
            document.getElementById('viewDentistModal').classList.add('hidden');
        }
        async function deleteDentist(id) {
            if (!confirm('Voulez-vous vraiment supprimer ce dentiste ?')) return;
            try {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                const response = await fetch('./api/dentists.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    window.location.reload();
                } else {
                    throw new Error(result.error);
                }
            } catch (error) {
                alert('Erreur: ' + error.message);
            }
        }
    </script>
</body>
</html>