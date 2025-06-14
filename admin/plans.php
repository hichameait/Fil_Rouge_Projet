<?php
require_once '../dashboard/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php');
    exit;
}

// Fetch all subscription plans
$plans = $pdo->query("SELECT * FROM subscription_plans ORDER BY price ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Plans | SmileDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <main class="ml-64 flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Gestion des Plans</h1>
                <button onclick="openPlanModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Nouveau Plan
                </button>
            </div>

            <!-- Plans Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($plans as $plan): ?>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($plan['name']) ?></h3>
                                <span class="inline-flex px-2 py-1 text-sm rounded-full <?= $plan['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $plan['is_active'] ? 'Actif' : 'Inactif' ?>
                                </span>
                            </div>
                            <p class="mt-2 text-2xl font-bold text-gray-900">
                                <?= number_format($plan['price'], 2) ?> €<span class="text-sm text-gray-500">/mois</span>
                            </p>
                            <p class="mt-4 text-gray-500"><?= htmlspecialchars($plan['description']) ?></p>
                            <div class="mt-6 space-y-4">
                                <?php 
                                $features = json_decode($plan['features'], true);
                                if (is_array($features)): 
                                    foreach ($features as $feature): 
                                ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        <span class="text-gray-600"><?= htmlspecialchars($feature) ?></span>
                                    </div>
                                <?php 
                                    endforeach; 
                                endif;
                                ?>
                            </div>
                            <div class="mt-6 flex justify-end space-x-3">
                                <button onclick="viewPlan(<?= $plan['id'] ?>)" class="text-blue-500 hover:text-blue-700" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editPlan(<?= $plan['id'] ?>)" class="text-blue-600 hover:text-blue-800" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="togglePlanStatus(<?= $plan['id'] ?>)" class="text-gray-600 hover:text-gray-800" title="Activer/Désactiver">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <button onclick="deletePlan(<?= $plan['id'] ?>)" class="text-red-600 hover:text-red-800" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <!-- Plan Modal -->
    <div id="planModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <form id="planForm" class="space-y-4">
                <input type="hidden" id="planId" name="plan_id">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom du plan</label>
                    <input type="text" id="planName" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="planDescription" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Durée (mois)</label>
                    <input type="number" id="planDuration" name="duration_months" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prix (€)</label>
                    <input type="number" step="0.01" id="planPrice" name="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fonctionnalités</label>
                    <div id="featuresContainer" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="features[]" class="flex-1 rounded-md border-gray-300 shadow-sm">
                            <button type="button" onclick="addFeatureField()" class="px-2 py-1 bg-blue-100 text-blue-600 rounded">+</button>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closePlanModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div id="viewContent"></div>
            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeViewModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md">Fermer</button>
            </div>
        </div>
    </div>

    <script>
        function openPlanModal(planData = null) {
            const modal = document.getElementById('planModal');
            const form = document.getElementById('planForm');
            form.reset();
            document.getElementById('planId').value = '';
            document.getElementById('featuresContainer').innerHTML = `
                <div class="flex gap-2">
                    <input type="text" name="features[]" class="flex-1 rounded-md border-gray-300 shadow-sm">
                    <button type="button" onclick="addFeatureField()" class="px-2 py-1 bg-blue-100 text-blue-600 rounded">+</button>
                </div>
            `;
            if (planData) {
                document.getElementById('planId').value = planData.id;
                document.getElementById('planName').value = planData.name;
                document.getElementById('planDescription').value = planData.description;
                document.getElementById('planDuration').value = planData.duration_months;
                document.getElementById('planPrice').value = planData.price;
                // Fill features
                const features = Array.isArray(planData.features) ? planData.features : (planData.features ? JSON.parse(planData.features) : []);
                const container = document.getElementById('featuresContainer');
                container.innerHTML = '';
                features.forEach(feature => addFeatureField(feature));
                if (features.length === 0) addFeatureField('');
            }
            modal.classList.remove('hidden');
        }

        function closePlanModal() {
            document.getElementById('planModal').classList.add('hidden');
        }

        function addFeatureField(value = '') {
            const container = document.getElementById('featuresContainer');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="features[]" value="${value.replace(/"/g, '&quot;')}" class="flex-1 rounded-md border-gray-300 shadow-sm">
                <button type="button" onclick="this.parentElement.remove()" class="px-2 py-1 bg-red-100 text-red-600 rounded">-</button>
            `;
            container.appendChild(div);
        }

        document.getElementById('planForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('action', formData.get('plan_id') ? 'update' : 'create');
            try {
                const response = await fetch('./api/plans.php', {
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

        async function editPlan(planId) {
            try {
                const response = await fetch(`./api/plans.php?action=get&id=${planId}`);
                const planData = await response.json();
                openPlanModal(planData);
            } catch (error) {
                alert('Erreur lors du chargement du plan');
            }
        }

        async function viewPlan(planId) {
            try {
                const response = await fetch(`./api/plans.php?action=get&id=${planId}`);
                const plan = await response.json();
                let html = `<h2 class="text-xl font-bold mb-2">${plan.name}</h2>
                    <p class="mb-2"><strong>Description:</strong> ${plan.description || ''}</p>
                    <p class="mb-2"><strong>Durée:</strong> ${plan.duration_months} mois</p>
                    <p class="mb-2"><strong>Prix:</strong> ${parseFloat(plan.price).toFixed(2)} €</p>
                    <div class="mb-2"><strong>Fonctionnalités:</strong><ul class="list-disc ml-6">`;
                let features = Array.isArray(plan.features) ? plan.features : (plan.features ? JSON.parse(plan.features) : []);
                features.forEach(f => html += `<li>${f}</li>`);
                html += '</ul></div>';
                html += `<p><strong>Statut:</strong> ${plan.is_active == 1 ? '<span class="text-green-600">Actif</span>' : '<span class="text-red-600">Inactif</span>'}</p>`;
                document.getElementById('viewContent').innerHTML = html;
                document.getElementById('viewModal').classList.remove('hidden');
            } catch (error) {
                alert('Erreur lors du chargement du plan');
            }
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        async function togglePlanStatus(planId) {
            if (!confirm('Voulez-vous vraiment changer le statut de ce plan ?')) return;
            try {
                const formData = new FormData();
                formData.append('action', 'toggle');
                formData.append('plan_id', planId);
                const response = await fetch('./api/plans.php', {
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

        async function deletePlan(planId) {
            if (!confirm('Voulez-vous vraiment supprimer ce plan ?')) return;
            try {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('plan_id', planId);
                const response = await fetch('./api/plans.php', {
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
