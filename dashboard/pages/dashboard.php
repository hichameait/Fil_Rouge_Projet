<?php
// Get dashboard statistics
$clinic_id = $_SESSION['clinic_id'];

// Total patients
$total_patients = fetchOne(
    "SELECT COUNT(*) as count FROM patients WHERE clinic_id = ?",
    [$clinic_id]
)['count'];

// Today's appointments
$today_appointments = fetchOne(
    "SELECT COUNT(*) as count FROM appointments WHERE clinic_id = ? AND DATE(appointment_date) = CURDATE()",
    [$clinic_id]
)['count'];

// Remaining appointments today
$remaining_appointments = fetchOne(
    "SELECT COUNT(*) as count FROM appointments WHERE clinic_id = ? AND DATE(appointment_date) = CURDATE() AND appointment_time > CURTIME() AND status = 'scheduled'",
    [$clinic_id]
)['count'];

// Average wait time (last 7 days)
$avg_wait_time = fetchOne(
    "SELECT AVG(wait_time) as avg_time FROM appointment_logs WHERE clinic_id = ? AND log_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)",
    [$clinic_id]
)['avg_time'] ?? 0;

// Monthly revenue
$monthly_revenue = fetchOne(
    "SELECT SUM(total_amount) as revenue FROM invoices WHERE clinic_id = ? AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND status = 'paid'",
    [$clinic_id]
)['revenue'] ?? 0;

// Upcoming appointments
$upcoming_appointments = fetchAll(
    "SELECT a.*, p.first_name, p.last_name, s.name as service_name, u.first_name as dentist_name
     FROM appointments a
     JOIN patients p ON a.patient_id = p.id
     JOIN services s ON a.service_id = s.id
     JOIN users u ON a.dentist_id = u.id
     WHERE a.clinic_id = ? AND DATE(a.appointment_date) = CURDATE() AND a.appointment_time > CURTIME() AND a.status = 'scheduled'
     ORDER BY a.appointment_time
     LIMIT 5",
    [$clinic_id]
);

// Recent activities
$recent_activities = fetchAll(
    "SELECT * FROM activities WHERE clinic_id = ? ORDER BY created_at DESC LIMIT 5",
    [$clinic_id]
);
?>

<div class="container p-6">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
        </div>

        <!-- Stats Cards -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center pb-2">
                    <h2 class="text-sm font-medium text-gray-600">Total Patients</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold"><?= number_format($total_patients) ?></div>
                <p class="text-xs text-gray-500">12% increase from last month</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center pb-2">
                    <h2 class="text-sm font-medium text-gray-600">Appointments Today</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold"><?= $today_appointments ?></div>
                <p class="text-xs text-gray-500"><?= $remaining_appointments ?> remaining for today</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center pb-2">
                    <h2 class="text-sm font-medium text-gray-600">Average Wait Time</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold"><?= round($avg_wait_time) ?> min</div>
                <p class="text-xs text-gray-500">2 min less than last week</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center pb-2">
                    <h2 class="text-sm font-medium text-gray-600">Revenue This Month</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold">$<?= number_format($monthly_revenue, 2) ?></div>
                <p class="text-xs text-gray-500">8% increase from last month</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Upcoming Appointments -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold">Upcoming Appointments</h2>
                    <p class="text-sm text-gray-500">Your schedule for the next 24 hours</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <?php if (empty($upcoming_appointments)): ?>
                            <p class="text-gray-500 text-center py-4">No upcoming appointments</p>
                        <?php else: ?>
                            <?php foreach ($upcoming_appointments as $appointment): ?>
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-medium">
                                            <?= strtoupper(substr($appointment['first_name'], 0, 1) . substr($appointment['last_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <p class="font-medium"><?= $appointment['first_name'] . ' ' . $appointment['last_name'] ?></p>
                                            <p class="text-sm text-gray-500"><?= $appointment['service_name'] ?></p>
                                            <p class="text-sm text-gray-500">
                                                <?= date('g:i A', strtotime($appointment['appointment_time'])) ?> 
                                                with Dr. <?= $appointment['dentist_name'] ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="text-gray-700 hover:text-gray-900 border border-gray-300 rounded-md px-3 py-1 text-sm">
                                            Reschedule
                                        </button>
                                        <button class="bg-blue-600 text-white rounded-md px-3 py-1 text-sm">
                                            View
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold">Recent Activity</h2>
                    <p class="text-sm text-gray-500">Latest updates and notifications</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <?php if (empty($recent_activities)): ?>
                            <p class="text-gray-500 text-center py-4">No recent activity</p>
                        <?php else: ?>
                            <?php foreach ($recent_activities as $activity): ?>
                                <?php
                                $icon_svgs = [
                                    'patient_added' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                                    'appointment_scheduled' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                                    'appointment_completed' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>',
                                    'payment_received' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                                    'document_uploaded' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10V6a5 5 0 0110 0v4M12 16v-4m0 0l-2 2m2-2l2 2" /></svg>',
                                ];
                                $icon_svg = $icon_svgs[$activity['type']] ?? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" /></svg>';
                                ?>
                                <div class="flex items-start space-x-4">
                                    <div class="bg-blue-100 p-2 rounded-full">
                                        <?= $icon_svg ?>
                                    </div>
                                    <div>
                                        <p class="font-medium"><?= $activity['title'] ?></p>
                                        <p class="text-sm text-gray-500"><?= $activity['description'] ?></p>
                                        <p class="text-xs text-gray-500"><?= date('M j, Y g:i A', strtotime($activity['created_at'])) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
