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
                    <p class="text-sm font-medium text-gray-600">Total des patients</p>
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
                    <p class="text-sm font-medium text-gray-600">Rendez-vous terminés</p>
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
                    <p class="text-sm font-medium text-gray-600">Revenu total</p>
                    <p class="text-2xl font-bold text-gray-900"><?= number_format(array_sum(array_column($monthly_revenue, 'revenue')), 2) ?> DH</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Taux de croissance</p>
                    <p class="text-2xl font-bold text-gray-900">+12,5%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Patient Registration Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Inscriptions patients (2024)</h3>
            <canvas id="patientsChart" width="400" height="200"></canvas>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenu mensuel (2024)</h3>
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Services and Appointments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Services -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Meilleurs services</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php foreach ($top_services as $service): ?>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900"><?= htmlspecialchars($service['name']) ?></p>
                                <p class="text-sm text-gray-600">
                                    <?= $service['appointment_count'] ?> rendez-vous
                                    <span class="text-gray-500">
                                        (<?= number_format((float)$service['service_price'], 2) ?> DH par service)
                                    </span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">
                                    <?= number_format((float)$service['total_revenue'], 2) ?> DH
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
                <h3 class="text-lg font-semibold text-gray-900">Statut des rendez-vous</h3>
            </div>
            <div class="p-6">
                <canvas id="appointmentStatusChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Add Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart data from PHP
const monthlyPatientsData = <?= json_encode($monthly_patients) ?>;
const monthlyRevenueData = <?= json_encode($monthly_revenue) ?>;
const appointmentStatsData = <?= json_encode($appointment_stats) ?>;

// Patient Registrations Chart
const patientsChart = new Chart(document.getElementById('patientsChart'), {
    type: 'bar',
    data: {
        labels: monthlyPatientsData.map(d => {
            const months = ['Janv', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
            return months[d.month - 1];
        }),
        datasets: [{
            label: 'Nouveaux patients',
            data: monthlyPatientsData.map(d => d.count),
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: false
            }
        }
    }
});

// Revenue Chart
const revenueChart = new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: monthlyRevenueData.map(d => {
            const months = ['Janv', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
            return months[d.month - 1];
        }),
        datasets: [{
            label: 'Revenu (MAD)',
            data: monthlyRevenueData.map(d => d.revenue),
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.5)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' MAD';
                    }
                }
            }
        },
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});

// Appointment Status Chart
const appointmentChart = new Chart(document.getElementById('appointmentStatusChart'), {
    type: 'doughnut',
    data: {
        labels: appointmentStatsData.map(d => {
            // Translate status to French
            const map = {
                completed: 'Terminé',
                scheduled: 'Planifié',
                in_progress: 'En cours',
                cancelled: 'Annulé',
                no_show: 'Absent'
            };
            return map[d.status] || d.status.charAt(0).toUpperCase() + d.status.slice(1);
        }),
        datasets: [{
            data: appointmentStatsData.map(d => d.count),
            backgroundColor: [
                'rgba(34, 197, 94, 0.8)',  // completed
                'rgba(59, 130, 246, 0.8)', // scheduled
                'rgba(245, 158, 11, 0.8)', // in_progress
                'rgba(239, 68, 68, 0.8)',  // cancelled
                'rgba(107, 114, 128, 0.8)' // no_show
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
            }
        }
    }
});
</script>
