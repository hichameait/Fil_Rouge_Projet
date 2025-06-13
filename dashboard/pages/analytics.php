<?php
$user_id = $_SESSION['user_id'];

// Get monthly patient registrations
$monthly_patients = fetchAll(
    "SELECT MONTH(created_at) as month, COUNT(*) as count 
     FROM patients 
     WHERE user_id = ? AND YEAR(created_at) = YEAR(CURDATE())
     GROUP BY MONTH(created_at)
     ORDER BY month",
    [$user_id]
);

// Get monthly revenue
$monthly_revenue = fetchAll(
    "SELECT MONTH(created_at) as month, SUM(total_amount) as revenue 
     FROM invoices 
     WHERE user_id = ? AND YEAR(created_at) = YEAR(CURDATE()) AND status = 'paid'
     GROUP BY MONTH(created_at)
     ORDER BY month",
    [$user_id]
);

// Get top services with proper price calculation
$top_services = fetchAll(
    "SELECT 
        bs.id,
        bs.name, 
        COUNT(a.id) as appointment_count,
        dsp.price as service_price,
        (COUNT(a.id) * dsp.price) as total_revenue
     FROM appointments a
     INNER JOIN base_services bs ON bs.id = a.base_service_id
     INNER JOIN dentist_service_prices dsp ON dsp.base_service_id = bs.id 
     WHERE a.user_id = ? 
        AND dsp.user_id = ?
        AND YEAR(a.appointment_date) = YEAR(CURDATE())
        AND bs.is_active = 1
        AND dsp.is_active = 1
     GROUP BY bs.id, bs.name, dsp.price
     ORDER BY COUNT(a.id) DESC
     LIMIT 10",
    [$user_id, $user_id]
);

// Debug query results
error_log("Top Services Query Results: " . print_r($top_services, true));

// Get appointment status distribution
$appointment_stats = fetchAll(
    "SELECT status, COUNT(*) as count 
     FROM appointments 
     WHERE user_id = ? AND YEAR(appointment_date) = YEAR(CURDATE())
     GROUP BY status",
    [$user_id]
);
?>

<div class="space-y-6">
    <!-- Analytics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Patients</p>
                    <p class="text-2xl font-bold text-gray-900"><?= array_sum(array_column($monthly_patients, 'count')) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-calendar-check text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed Appointments</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <?= array_sum(array_column(array_filter($appointment_stats, function($stat) { return $stat['status'] === 'completed'; }), 'count')) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-dollar-sign text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">$<?= number_format(array_sum(array_column($monthly_revenue, 'revenue')), 2) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Growth Rate</p>
                    <p class="text-2xl font-bold text-gray-900">+12.5%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Patient Registration Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Patient Registrations (2024)</h3>
            <canvas id="patientsChart" width="400" height="200"></canvas>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Revenue (2024)</h3>
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Services and Appointments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Services -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Services</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php foreach ($top_services as $service): ?>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900"><?= htmlspecialchars($service['name']) ?></p>
                                <p class="text-sm text-gray-600">
                                    <?= $service['appointment_count'] ?> appointment<?= $service['appointment_count'] !== 1 ? 's' : '' ?>
                                    <span class="text-gray-500">
                                        (<?= number_format((float)$service['service_price'], 2) ?> MAD per service)
                                    </span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">
                                    <?= number_format((float)$service['total_revenue'], 2) ?> MAD
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Appointment Status Distribution -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Appointment Status</h3>
            </div>
            <div class="p-6">
                <canvas id="appointmentStatusChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Chart data from PHP
const monthlyPatientsData = <?= json_encode($monthly_patients) ?>;
const monthlyRevenueData = <?= json_encode($monthly_revenue) ?>;
const appointmentStatsData = <?= json_encode($appointment_stats) ?>;
</script>
