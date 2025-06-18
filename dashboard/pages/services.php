<?php
$user_id = $_SESSION['user_id'];

// Get all service categories
$categories = fetchAll(
    "SELECT * FROM service_categories ORDER BY name"
);

// Get all base services (global and custom) and join with dentist_service_prices for this user
$services = fetchAll(
    "SELECT bs.*, sc.name as category_name, dsp.price as custom_price
     FROM base_services bs
     LEFT JOIN service_categories sc ON bs.category_id = sc.id
     LEFT JOIN dentist_service_prices dsp ON dsp.base_service_id = bs.id AND dsp.user_id = ?
     WHERE bs.is_active = 1
     ORDER BY sc.name, bs.name",
    [$user_id]
);
?>
<div class="space-y-6">
    <!-- Header -->
    <!-- <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Services Catalog</h2>
            <p class="text-gray-600">Manage your dental services and procedures</p>
        </div>
        <button id="addServiceBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Add Service
        </button>
    </div> -->

    <!-- Service Categories -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Catégories de services</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($categories as $category): ?>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900"><?= $category['name'] ?></h4>
                        <p class="text-sm text-gray-600 mt-1"><?= $category['description'] ?></p>
                        <div class="mt-3 flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 text-sm edit-category-btn" 
                                    data-id="<?= $category['id'] ?>" 
                                    data-name="<?= htmlspecialchars($category['name']) ?>" 
                                    data-description="<?= htmlspecialchars($category['description']) ?>">
                                Modifier
                            </button>
                            <button class="text-red-600 hover:text-red-800 text-sm delete-category-btn" 
                                    data-id="<?= $category['id'] ?>">
                                Supprimer
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Services List -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Tous les services</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix (MAD)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($service['name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($service['description']) ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= htmlspecialchars($service['category_name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= (int)$service['duration'] ?> min
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <form method="post" action="./dashboard/api/save_service_price.php" class="inline price-form">
                                    <input type="hidden" name="base_service_id" value="<?= $service['id'] ?>">
                                    <input type="number" step="0.01" min="0" name="price" value="<?= $service['custom_price'] !== null ? $service['custom_price'] : '' ?>" placeholder="Définir le prix" style="width:80px;" required>
                                    <button type="submit" class="ml-2 text-blue-600 hover:text-blue-900">Enregistrer</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <!-- Optionally add more actions here -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tooth Chart for Service Selection -->
    <!-- <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Tooth Selection Chart</h3>
            <p class="text-sm text-gray-600">Used for tooth-specific procedures</p>
        </div>
    </div> -->

    <!-- Edit Category Modal (hidden by default) -->
    <div id="editCategoryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Modifier la catégorie</h3>
            <form id="editCategoryForm">
                <input type="hidden" name="id" id="editCategoryId">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" name="name" id="editCategoryName" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="editCategoryDescription" class="w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancelEditCategory" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Annuler</button>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Service Modal (hidden by default) -->
    <div id="editServiceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Edit Service</h3>
            <form id="editServiceForm">
                <input type="hidden" name="id" id="editServiceId">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="editServiceName" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="editServiceDescription" class="w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" id="editServiceCategory" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration (min)</label>
                    <input type="number" name="duration" id="editServiceDuration" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
                    <input type="number" step="0.01" name="price" id="editServicePrice" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <!-- <div class="mb-3">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="tooth" id="editServiceTooth" class="form-checkbox">
                        <span class="ml-2 text-sm text-gray-700">Tooth Specific</span>
                    </label>
                </div> -->
                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancelEditService" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal (hidden by default) -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
            <h3 class="text-lg font-semibold mb-4">Confirmer la suppression</h3>
            <p class="mb-4">Êtes-vous sûr de vouloir supprimer cet élément ?</p>
            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelDelete" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Annuler</button>
                <button type="button" id="confirmDelete" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Supprimer</button>
            </div>
        </div>
    </div>

    <script>
    // Category Edit
    document.querySelectorAll('.edit-category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('editCategoryId').value = this.dataset.id;
            document.getElementById('editCategoryName').value = this.dataset.name;
            document.getElementById('editCategoryDescription').value = this.dataset.description;
            document.getElementById('editCategoryModal').classList.remove('hidden');
        });
    });
    document.getElementById('cancelEditCategory').onclick = function() {
        document.getElementById('editCategoryModal').classList.add('hidden');
    };

    // Service Edit
    document.querySelectorAll('.edit-service-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('editServiceId').value = this.dataset.id;
            document.getElementById('editServiceName').value = this.dataset.name;
            document.getElementById('editServiceDescription').value = this.dataset.description;
            document.getElementById('editServiceCategory').value = this.dataset.category;
            document.getElementById('editServiceDuration').value = this.dataset.duration;
            document.getElementById('editServicePrice').value = this.dataset.price;
            document.getElementById('editServiceTooth').checked = this.dataset.tooth == "1";
            document.getElementById('editServiceModal').classList.remove('hidden');
        });
    });
    document.getElementById('cancelEditService').onclick = function() {
        document.getElementById('editServiceModal').classList.add('hidden');
    };

    // Delete logic
    let deleteType = '', deleteId = '';
    document.querySelectorAll('.delete-category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteType = 'category';
            deleteId = this.dataset.id;
            document.getElementById('deleteConfirmModal').classList.remove('hidden');
        });
    });
    document.querySelectorAll('.delete-service-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteType = 'service';
            deleteId = this.dataset.id;
            document.getElementById('deleteConfirmModal').classList.remove('hidden');
        });
    });
    document.getElementById('cancelDelete').onclick = function() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
        deleteType = ''; deleteId = '';
    };
    document.getElementById('confirmDelete').onclick = function() {
        if (deleteType && deleteId) {
            // You should implement AJAX or form submission here
            // Example: window.location = `delete.php?type=${deleteType}&id=${deleteId}`;
            alert('Suppression de ' + deleteType + ' avec ID ' + deleteId);
            document.getElementById('deleteConfirmModal').classList.add('hidden');
        }
    };

    // Robust AJAX submit for Edit Service form
    (function() {
        var form = document.getElementById('editServiceForm');
        if (!form) return;
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(form);
            fetch('/pfa/api/save_service.php', {
                method: 'POST',
                body: formData
            })
            .then(async function(res) {
                if (!res.ok) {
                    let data = {};
                    try { data = await res.json(); } catch {}
                    alert(data.error || 'Error saving service');
                    return;
                }
                window.location = 'index.php?page=services';
            })
            .catch(function() {
                alert('Network error');
            });
        });
    })();

    // AJAX price update for service price form
    const forms = document.querySelectorAll('.price-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('/Fil_Rouge_Projet/dashboard/api/save_service_price.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Price updated');
                } else {
                    alert(data.error || 'Error updating price');
                }
            })
            .catch(() => alert('Network error'));
        });
    });
    </script>
</div>
