<?php
$user_id = $_SESSION['user_id'];

// Handle filters
$type_filter = $_GET['type'] ?? '';
$patient_filter = $_GET['patient'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$where_conditions = ["d.user_id = ?"];
$params = [$user_id];

if (!empty($type_filter)) {
    $where_conditions[] = "d.type = ?";
    $params[] = $type_filter;
}

if (!empty($patient_filter)) {
    $where_conditions[] = "d.patient_id = ?";
    $params[] = $patient_filter;
}

if (!empty($search)) {
    $where_conditions[] = "(d.title LIKE ? OR d.description LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param]);
}

$where_clause = implode(' AND ', $where_conditions);

// Get documents
$documents = fetchAll(
    "SELECT d.*, p.first_name, p.last_name, u.first_name as uploaded_by_name
     FROM documents d
     LEFT JOIN patients p ON d.patient_id = p.id
     LEFT JOIN users u ON d.uploaded_by = u.id
     WHERE $where_clause
     ORDER BY d.created_at DESC",
    $params
);

// Get patients for filter
$patients = fetchAll(
    "SELECT id, first_name, last_name FROM patients WHERE user_id = ? ORDER BY first_name, last_name",
    [$user_id]
);

// Get document statistics
$stats = fetchOne(
    "SELECT 
        COUNT(*) as total_documents,
        COUNT(CASE WHEN type = 'xray' THEN 1 END) as xrays,
        COUNT(CASE WHEN type = 'treatment_plan' THEN 1 END) as treatment_plans,
        COUNT(CASE WHEN type = 'consent_form' THEN 1 END) as consent_forms
     FROM documents 
     WHERE user_id = ?",
    [$user_id]
);
?>

<div class="container p-6">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold tracking-tight">Documents</h1>
            <button id="uploadDocumentBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                </svg>
                Ajouter un document
            </button>
        </div>

        <!-- Statistics -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-600">Documents totaux</span>
                </div>
                <div class="text-2xl font-bold"><?= $stats['total_documents'] ?></div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-600">Radiographies</span>
                </div>
                <div class="text-2xl font-bold"><?= $stats['xrays'] ?></div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-600">Plans de traitement</span>
                </div>
                <div class="text-2xl font-bold"><?= $stats['treatment_plans'] ?></div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-600">Formulaires de consentement</span>
                </div>
                <div class="text-2xl font-bold"><?= $stats['consent_forms'] ?></div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <input type="hidden" name="page" value="documents">
                <div class="flex-1 min-w-64">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="search" name="search" value="<?= htmlspecialchars($search) ?>"
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Rechercher des documents...">
                    </div>
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de document</label>
                    <select id="type" name="type" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tous les types</option>
                        <option value="xray" <?= $type_filter === 'xray' ? 'selected' : '' ?>>Radiographie</option>
                        <option value="treatment_plan" <?= $type_filter === 'treatment_plan' ? 'selected' : '' ?>>Plan de traitement</option>
                        <option value="consent_form" <?= $type_filter === 'consent_form' ? 'selected' : '' ?>>Formulaire de consentement</option>
                        <option value="report" <?= $type_filter === 'report' ? 'selected' : '' ?>>Rapport</option>
                        <option value="prescription" <?= $type_filter === 'prescription' ? 'selected' : '' ?>>Ordonnance</option>
                        <option value="other" <?= $type_filter === 'other' ? 'selected' : '' ?>>Autre</option>
                    </select>
                </div>
                <div>
                    <label for="patient" class="block text-sm font-medium text-gray-700 mb-1">Patient</label>
                    <select id="patient" name="patient" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tous les patients</option>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?= $patient['id'] ?>" <?= $patient_filter == $patient['id'] ? 'selected' : '' ?>>
                                <?= $patient['first_name'] . ' ' . $patient['last_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                    </svg>
                    Filtrer
                </button>
                <a href="index.php?page=documents" class="text-gray-600 hover:text-gray-800 px-3 py-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Réinitialiser
                </a>
            </form>
        </div>

        <!-- Documents Grid -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Documents</h3>
            </div>
            <?php if (empty($documents)): ?>
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto text-gray-300 mb-4" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2m-6 4h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun document trouvé</h3>
                    <p class="text-gray-500 mb-4">Ajoutez votre premier document pour commencer.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    <?php foreach ($documents as $document): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                        <?php
                                        switch ($document['type']) {
                                            case 'xray':
                                                echo 'bg-blue-100 text-blue-600';
                                                break;
                                            case 'treatment_plan':
                                                echo 'bg-green-100 text-green-600';
                                                break;
                                            case 'consent_form':
                                                echo 'bg-purple-100 text-purple-600';
                                                break;
                                            case 'report':
                                                echo 'bg-yellow-100 text-yellow-600';
                                                break;
                                            case 'prescription':
                                                echo 'bg-red-100 text-red-600';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-600';
                                        }
                                        ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <span class="text-xs font-medium text-gray-500 uppercase">
                                            <?php
                                            switch ($document['type']) {
                                                case 'xray': echo 'Radiographie'; break;
                                                case 'treatment_plan': echo 'Plan de traitement'; break;
                                                case 'consent_form': echo 'Formulaire de consentement'; break;
                                                case 'report': echo 'Rapport'; break;
                                                case 'prescription': echo 'Ordonnance'; break;
                                                case 'other': echo 'Autre'; break;
                                                default: echo str_replace('_', ' ', $document['type']);
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="relative">
                                    <button class="document-menu-btn text-gray-400 hover:text-gray-600" data-id="<?= $document['id'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01" />
                                        </svg>
                                    </button>
                                    <div class="document-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                                        <a href="#" class="view-document-btn block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-id="<?= $document['id'] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm2 2a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            Voir
                                        </a>
                                        <a href="#" class="download-document-btn block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-id="<?= $document['id'] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Télécharger
                                        </a>
                                        <a href="#" class="edit-document-btn block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-id="<?= $document['id'] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m-7-7h14" />
                                            </svg>
                                            Modifier
                                        </a>
                                        <div class="border-t border-gray-100"></div>
                                        <a href="#" class="delete-document-btn block px-4 py-2 text-sm text-red-600 hover:bg-red-50" data-id="<?= $document['id'] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Supprimer
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-2"><?= htmlspecialchars($document['title']) ?></h4>
                            <?php if (!empty($document['description'])): ?>
                                <p class="text-sm text-gray-600 mb-3"><?= htmlspecialchars($document['description']) ?></p>
                            <?php endif; ?>
                            <div class="space-y-2">
                                <?php if ($document['patient_id']): ?>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.797.657 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <?= $document['first_name'] . ' ' . $document['last_name'] ?>
                                    </div>
                                <?php endif; ?>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <?= date('d M Y', strtotime($document['created_at'])) ?>
                                </div>
                                <?php if ($document['uploaded_by_name']): ?>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16m16-8H4" />
                                        </svg>
                                        Ajouté par <?= $document['uploaded_by_name'] ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($document['file_size']): ?>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2m-6 4h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                        <?= formatFileSize($document['file_size']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}
?>
