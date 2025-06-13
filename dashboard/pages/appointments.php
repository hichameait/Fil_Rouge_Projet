<?php
$dentist_id = $_SESSION['user_id'];

// Handle filters
$date_filter = $_GET['date'] ?? '';
$status_filter = $_GET['status'] ?? '';

// Get appointments for the selected filters
$where_conditions = ["a.dentist_id = ?"];
$params = [$dentist_id];

if (!empty($date_filter)) {
    $where_conditions[] = "DATE(a.appointment_date) = ?";
    $params[] = $date_filter;
}

if (!empty($status_filter)) {
    $where_conditions[] = "a.status = ?";
    $params[] = $status_filter;
}

$where_clause = implode(' AND ', $where_conditions);

$appointments = fetchAll(
    "SELECT a.*, p.first_name, p.last_name, p.phone, bs.name as service_name, 
            u.first_name as dentist_first_name, u.last_name as dentist_last_name
     FROM appointments a
     JOIN patients p ON a.patient_id = p.id
     JOIN base_services bs ON a.base_service_id = bs.id
     JOIN users u ON a.dentist_id = u.id
     WHERE $where_clause
     ORDER BY a.appointment_date DESC, a.appointment_time DESC",
    $params
);

// Statistics: show for all appointments (or filtered date if filter is set)
if (!empty($date_filter)) {
    $stats = fetchOne(
        "SELECT 
            COUNT(*) as total,
            COUNT(CASE WHEN status = 'scheduled' THEN 1 END) as scheduled,
            COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
            COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled
         FROM appointments 
         WHERE dentist_id = ? AND DATE(appointment_date) = ?",
        [$dentist_id, $date_filter]
    );
} else {
    $stats = fetchOne(
        "SELECT 
            COUNT(*) as total,
            COUNT(CASE WHEN status = 'scheduled' THEN 1 END) as scheduled,
            COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
            COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled
         FROM appointments 
         WHERE dentist_id = ?",
        [$dentist_id]
    );
}
?>

<div class="container p-6">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold tracking-tight">Appointments</h1>
            <button id="newAppointmentBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Appointment
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <input type="hidden" name="page" value="appointments">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" id="date" name="date" value="<?= htmlspecialchars($date_filter) ?>"
                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="scheduled" <?= $status_filter === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                        <option value="confirmed" <?= $status_filter === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                        <option value="in_progress" <?= $status_filter === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="completed" <?= $status_filter === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        <option value="no_show" <?= $status_filter === 'no_show' ? 'selected' : '' ?>>No Show</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                    </svg>
                    Filter
                </button>
            </form>
        </div>

        <!-- Statistics -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-600">Total</span>
                </div>
                <div class="text-2xl font-bold"><?= $stats['total'] ?></div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-600">Scheduled</span>
                </div>
                <div class="text-2xl font-bold"><?= $stats['scheduled'] ?></div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-medium text-gray-600">Completed</span>
                </div>
                <div class="text-2xl font-bold"><?= $stats['completed'] ?></div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="text-sm font-medium text-gray-600">Cancelled</span>
                </div>
                <div class="text-2xl font-bold"><?= $stats['cancelled'] ?></div>
            </div>
        </div>

        <!-- Appointments List -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <?php if (!empty($date_filter)): ?>
                        Appointments for <?= date('F j, Y', strtotime($date_filter)) ?>
                    <?php else: ?>
                        All Appointments
                    <?php endif; ?>
                </h3>
            </div>
            <?php if (empty($appointments)): ?>
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto text-gray-300 mb-4" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No appointments found</h3>
                    <p class="text-gray-500 mb-4">No appointments scheduled for this date.</p>
                    <button id="scheduleFirstBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Schedule Appointment
                    </button>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach ($appointments as $appointment): ?>
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-medium">
                                        <?= strtoupper(substr($appointment['first_name'], 0, 1) . substr($appointment['last_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="font-medium"><?= $appointment['first_name'] . ' ' . $appointment['last_name'] ?></p>
                                        <p class="text-sm text-gray-500"><?= $appointment['service_name'] ?></p>
                                        <p class="text-sm text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 mr-1 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3-1.343 3-3 3-3-1.343-3-3z" />
                                            </svg>
                                            Dr. <?= $appointment['dentist_first_name'] . ' ' . $appointment['dentist_last_name'] ?>
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            <?= date('Y-m-d', strtotime($appointment['appointment_date'])) ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-6">
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">
                                            <?= date('g:i A', strtotime($appointment['appointment_time'])) ?>
                                        </p>
                                        <p class="text-sm text-gray-500"><?= $appointment['duration'] ?> minutes</p>
                                    </div>
                                    <div>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            <?php
                                            switch ($appointment['status']) {
                                                case 'scheduled':
                                                    echo 'bg-yellow-100 text-yellow-800';
                                                    break;
                                                case 'confirmed':
                                                    echo 'bg-blue-100 text-blue-800';
                                                    break;
                                                case 'in_progress':
                                                    echo 'bg-purple-100 text-purple-800';
                                                    break;
                                                case 'completed':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'cancelled':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                case 'no_show':
                                                    echo 'bg-gray-100 text-gray-800';
                                                    break;
                                            }
                                            ?>">
                                            <?= ucfirst(str_replace('_', ' ', $appointment['status'])) ?>
                                        </span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <?php if ($appointment['status'] === 'scheduled'): ?>
                                            <button class="confirm-appointment-btn bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700" 
                                                    data-id="<?= $appointment['id'] ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Confirm
                                            </button>
                                            <button class="start-appointment-btn bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700" 
                                                    data-id="<?= $appointment['id'] ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Start
                                            </button>
                                        <?php elseif ($appointment['status'] === 'confirmed'): ?>
                                            <button class="start-appointment-btn bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700" 
                                                    data-id="<?= $appointment['id'] ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Start
                                            </button>
                                        <?php elseif ($appointment['status'] === 'in_progress'): ?>
                                            <button class="complete-appointment-btn bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700" 
                                                    data-id="<?= $appointment['id'] ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Complete
                                            </button>
                                        <?php endif; ?>
                                        <button class="reschedule-appointment-btn text-blue-600 hover:text-blue-800 px-3 py-1 border border-blue-600 rounded text-sm flex items-center" 
                                                data-id="<?= $appointment['id'] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Reschedule
                                        </button>
                                        <div class="relative">
                                            <button class="appointment-menu-btn text-gray-600 hover:text-gray-800 px-2 py-1" 
                                                    data-id="<?= $appointment['id'] ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01" />
                                                </svg>
                                            </button>
                                            <div class="appointment-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 view-details-btn" data-action="view" data-id="<?= $appointment['id'] ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm2 2a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    View Details
                                                </a>
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 edit-appointment-btn" data-action="edit" data-id="<?= $appointment['id'] ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m-7-7h14" />
                                                    </svg>
                                                    Edit
                                                </a>
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-action="call">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm0 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2zm10-10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zm0 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                                    </svg>
                                                    Call Patient
                                                </a>
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-action="sms">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m-2 8a9 9 0 110-18 9 9 0 010 18z" />
                                                    </svg>
                                                    Send SMS
                                                </a>
                                                <div class="border-t border-gray-100"></div>
                                                <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50" data-action="cancel">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Cancel
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($appointment['notes'])): ?>
                                <div class="mt-3 ml-16">
                                    <p class="text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 mr-1 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9" />
                                        </svg>
                                        <?= htmlspecialchars($appointment['notes']) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Appointment Details Modal -->
<div id="appointmentDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <button id="closeAppointmentModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4">Appointment Details</h2>
        <div id="appointmentDetailsContent">
            <div class="text-center text-gray-400">Loading...</div>
        </div>
    </div>
</div>

<!-- Appointment Edit Modal -->
<div id="appointmentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <button id="closeAppointmentEditModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4">Edit Appointment</h2>
        <form id="appointmentForm">
            <input type="hidden" name="id" id="appointment-id">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Patient</label>
                <select id="patient-select" name="patient_id" class="w-full border rounded px-2 py-1"></select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Service</label>
                <select id="service-select" name="service_id" class="w-full border rounded px-2 py-1"></select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Date</label>
                <input type="date" id="appointment-date" name="appointment_date" class="w-full border rounded px-2 py-1">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Time</label>
                <input type="time" id="appointment-time" name="appointment_time" class="w-full border rounded px-2 py-1">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Duration (minutes)</label>
                <input type="number" id="duration" name="duration" class="w-full border rounded px-2 py-1">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select id="status" name="status" class="w-full border rounded px-2 py-1">
                    <option value="scheduled">Scheduled</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="no_show">No Show</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Notes</label>
                <textarea id="notes" name="notes" class="w-full border rounded px-2 py-1"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Generic Action Modal (for call, sms, etc.) -->
<div id="actionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <button id="closeActionModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
        <h2 id="actionModalTitle" class="text-xl font-bold mb-4"></h2>
        <div id="actionModalContent"></div>
    </div>
</div>

<script>
// Centralized modal logic for appointments
function openModal(modalId) {
    document.querySelectorAll('.modal').forEach(m => m.classList.add('hidden'));
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // --- MODAL CLOSE BUTTONS ---
    document.getElementById('closeAppointmentModal').addEventListener('click', function() {
        closeModal('appointmentDetailsModal');
    });
    document.getElementById('closeAppointmentEditModal').addEventListener('click', function() {
        closeModal('appointmentModal');
    });
    // --- MODAL BACKDROP CLICK ---
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });

    // --- APPOINTMENT MENU LOGIC ---
    document.querySelectorAll('.appointment-menu-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            // Close all other menus first
            document.querySelectorAll('.appointment-menu').forEach(function(menu) {
                menu.classList.add('hidden');
            });
            var menu = this.nextElementSibling;
            if (menu) menu.classList.toggle('hidden');
        });
    });
    // Close menus when clicking outside any menu or button
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.appointment-menu') && !e.target.closest('.appointment-menu-btn')) {
            document.querySelectorAll('.appointment-menu').forEach(function(menu) {
                menu.classList.add('hidden');
            });
        }
    });

    // --- APPOINTMENT MENU ACTIONS ---
    document.querySelectorAll('.appointment-menu a[data-action]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const action = this.getAttribute('data-action');
            const appointmentId = this.getAttribute('data-id');
            // Always close all menus before opening modal
            document.querySelectorAll('.appointment-menu').forEach(menu => menu.classList.add('hidden'));
            if (action === 'view') {
                // Load and show view modal
                openModal('appointmentDetailsModal');
                const content = document.getElementById('appointmentDetailsContent');
                content.innerHTML = '<div class="text-center text-gray-400">Loading...</div>';
                fetch(`http://localhost/Fil_Rouge_Projet/dashboard/api/appointments.php?id=${appointmentId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.appointment) {
                            var a = data.appointment;
                            content.innerHTML = `
                                <div class="mb-2"><span class="font-semibold">Patient:</span> ${a.first_name} ${a.last_name}</div>
                                <div class="mb-2"><span class="font-semibold">Phone:</span> ${a.phone}</div>
                                <div class="mb-2"><span class="font-semibold">Service:</span> ${a.service_name}</div>
                                <div class="mb-2"><span class="font-semibold">Date:</span> ${a.appointment_date}</div>
                                <div class="mb-2"><span class="font-semibold">Time:</span> ${a.appointment_time}</div>
                                <div class="mb-2"><span class="font-semibold">Duration:</span> ${a.duration} minutes</div>
                                <div class="mb-2"><span class="font-semibold">Status:</span> ${a.status.toUpperCase()}</div>
                                <div class="mb-2"><span class="font-semibold">Notes:</span> ${a.notes || '<span class="text-gray-400">No notes</span>'}</div>
                            `;
                        } else {
                            content.innerHTML = '<div class="text-red-500">Failed to load appointment details.</div>';
                        }
                    });
            } else if (action === 'edit') {
                // Load and show edit modal
                Promise.all([
                    fetch('http://localhost/Fil_Rouge_Projet/dashboard/api/patients.php').then(r => r.json()),
                    fetch('http://localhost/Fil_Rouge_Projet/dashboard/api/services.php').then(r => r.json())
                ]).then(([patients, services]) => {
                    document.getElementById('patient-select').innerHTML = patients.map(p => 
                        `<option value="${p.id}">${p.first_name} ${p.last_name}</option>`
                    ).join('');
                    document.getElementById('service-select').innerHTML = services.map(s => 
                        `<option value="${s.id}">${s.name}</option>`
                    ).join('');
                    return fetch(`http://localhost/Fil_Rouge_Projet/dashboard/api/appointments.php?id=${appointmentId}`);
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.appointment) {
                        var a = data.appointment;
                        document.getElementById('appointment-id').value = a.id;
                        document.getElementById('patient-select').value = a.patient_id;
                        document.getElementById('service-select').value = a.service_id;
                        document.getElementById('appointment-date').value = a.appointment_date;
                        document.getElementById('appointment-time').value = a.appointment_time;
                        document.getElementById('duration').value = a.duration;
                        document.getElementById('status').value = a.status;
                        document.getElementById('notes').value = a.notes || '';
                        openModal('appointmentModal');
                    }
                });
            }
        });
    });
});
</script>