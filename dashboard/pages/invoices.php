<?php
$user_id = $_SESSION['user_id'];

// Handle filters
$status_filter = $_GET['status'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$where_conditions = ["i.user_id = ?"];
$params = [$user_id];

if (!empty($status_filter)) {
    $where_conditions[] = "i.status = ?";
    $params[] = $status_filter;
}

if (!empty($date_from)) {
    $where_conditions[] = "DATE(i.created_at) >= ?";
    $params[] = $date_from;
}

if (!empty($date_to)) {
    $where_conditions[] = "DATE(i.created_at) <= ?";
    $params[] = $date_to;
}

if (!empty($search)) {
    $where_conditions[] = "(i.invoice_number LIKE ? OR p.first_name LIKE ? OR p.last_name LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
}

$where_clause = implode(' AND ', $where_conditions);

// Get invoices
$invoices = fetchAll(
    "SELECT i.*, p.first_name, p.last_name, p.email
     FROM invoices i
     JOIN patients p ON i.patient_id = p.id
     WHERE $where_clause
     ORDER BY i.created_at DESC",
    $params
);

// Get invoice statistics
$stats = fetchOne(
    "SELECT 
        COUNT(*) as total_invoices,
        SUM(CASE WHEN status = 'paid' THEN total_amount ELSE 0 END) as paid_amount,
        SUM(CASE WHEN status = 'overdue' THEN total_amount ELSE 0 END) as overdue_amount,
        SUM(CASE WHEN status IN ('draft', 'sent') THEN total_amount ELSE 0 END) as pending_amount
     FROM invoices 
     WHERE user_id = ?",
    [$user_id]
);
?>

<div class="container p-6">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold tracking-tight">Invoices</h1>
            <button id="createInvoiceBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Invoice
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <input type="hidden" name="page" value="invoices">
                <div class="flex-1 min-w-64">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="search" name="search" value="<?= htmlspecialchars($search) ?>"
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Search by invoice number or patient name...">
                    </div>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="draft" <?= $status_filter === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="sent" <?= $status_filter === 'sent' ? 'selected' : '' ?>>Sent</option>
                        <option value="paid" <?= $status_filter === 'paid' ? 'selected' : '' ?>>Paid</option>
                        <option value="overdue" <?= $status_filter === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                        <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" id="date_from" name="date_from" value="<?= $date_from ?>"
                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" id="date_to" name="date_to" value="<?= $date_to ?>"
                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                    </svg>
                    Filter
                </button>
                <a href="index.php?page=invoices" class="text-gray-600 hover:text-gray-800 px-3 py-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear
                </a>
            </form>
        </div>

        <!-- Invoices Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Invoices</h3>
            </div>
            <?php if (empty($invoices)): ?>
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto text-gray-300 mb-4" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2m-6 4h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices found</h3>
                    <p class="text-gray-500 mb-4">Create your first invoice to get started.</p>
                    <!-- <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Invoice
                    </button> -->
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($invoices as $invoice): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?= $invoice['invoice_number'] ?></div>
                                            <div class="text-sm text-gray-500"><?= date('M j, Y', strtotime($invoice['created_at'])) ?></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= $invoice['first_name'] . ' ' . $invoice['last_name'] ?>
                                            </div>
                                            <div class="text-sm text-gray-500"><?= $invoice['email'] ?></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">$<?= number_format($invoice['total_amount'], 2) ?></div>
                                        <?php if ($invoice['discount_amount'] > 0): ?>
                                            <div class="text-sm text-gray-500">Discount: $<?= number_format($invoice['discount_amount'], 2) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            <?php
                                            switch ($invoice['status']) {
                                                case 'draft':
                                                    echo 'bg-gray-100 text-gray-800';
                                                    break;
                                                case 'sent':
                                                    echo 'bg-blue-100 text-blue-800';
                                                    break;
                                                case 'paid':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'overdue':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                case 'cancelled':
                                                    echo 'bg-gray-100 text-gray-800';
                                                    break;
                                            }
                                            ?>">
                                            <?= ucfirst($invoice['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= $invoice['due_date'] ? date('M j, Y', strtotime($invoice['due_date'])) : '-' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button class="view-invoice-btn text-blue-600 hover:text-blue-900" data-id="<?= $invoice['id'] ?>" title="View Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="#e0f2fe"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </button>
                                            <button class="edit-invoice-btn text-green-600 hover:text-green-800" data-id="<?= $invoice['id'] ?>" title="Edit Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <rect x="4" y="4" width="16" height="16" rx="2" fill="#bbf7d0"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m-7-7h14" />
                                                </svg>
                                            </button>
                                            <button class="download-invoice-btn text-purple-600 hover:text-purple-900" data-id="<?= $invoice['id'] ?>" title="Download PDF">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <rect x="4" y="4" width="16" height="16" rx="2" fill="#ede9fe"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </button>
                                            <?php if ($invoice['status'] !== 'paid'): ?>
                                                <button class="mark-paid-btn text-emerald-600 hover:text-emerald-800" data-id="<?= $invoice['id'] ?>" title="Mark as Paid">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <circle cx="12" cy="12" r="10" fill="#d1fae5"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                            <button class="send-invoice-btn text-sky-600 hover:text-sky-800" data-id="<?= $invoice['id'] ?>" title="Send Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <rect x="4" y="4" width="16" height="16" rx="2" fill="#bae6fd"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16m16-8H4" />
                                                </svg>
                                            </button>
                                            <button class="delete-invoice-btn text-red-600 hover:text-red-800" data-id="<?= $invoice['id'] ?>" title="Delete Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <rect x="4" y="4" width="16" height="16" rx="2" fill="#fee2e2"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
