<?php
// Get dashboard statistics from database
$stmt = $pdo->query("SELECT COUNT(*) as total_patients FROM patients");
$total_patients = $stmt->fetch()['total_patients'];

$stmt = $pdo->query("SELECT COUNT(*) as today_appointments FROM appointments WHERE DATE(appointment_date) = CURDATE()");
$today_appointments = $stmt->fetch()['today_appointments'];

$stmt = $pdo->query("SELECT COUNT(*) as remaining_appointments FROM appointments WHERE DATE(appointment_date) = CURDATE() AND appointment_time > CURTIME()");
$remaining_appointments = $stmt->fetch()['remaining_appointments'];

$stmt = $pdo->query("SELECT AVG(wait_time) as avg_wait_time FROM appointment_logs WHERE DATE(log_date) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$avg_wait_time = round($stmt->fetch()['avg_wait_time']);

$stmt = $pdo->query("SELECT SUM(amount) as monthly_revenue FROM payments WHERE MONTH(payment_date) = MONTH(CURDATE()) AND YEAR(payment_date) = YEAR(CURDATE())");
$monthly_revenue = $stmt->fetch()['monthly_revenue'];

// Get upcoming appointments
$stmt = $pdo->prepare("
    SELECT a.id, p.first_name, p.last_name, a.procedure_name, 
           TIME_FORMAT(a.appointment_time, '%h:%i %p') as formatted_time, 
           a.duration
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    WHERE DATE(a.appointment_date) = CURDATE() AND a.appointment_time > CURTIME()
    ORDER BY a.appointment_time
    LIMIT 3
");
$stmt->execute();
$upcoming_appointments = $stmt->fetchAll();

// Get recent activities
$stmt = $pdo->prepare("
    SELECT a.id, a.activity_type, a.title, a.description, 
           CASE
               WHEN TIMESTAMPDIFF(MINUTE, a.created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, a.created_at, NOW()), ' minutes ago')
               WHEN TIMESTAMPDIFF(HOUR, a.created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, a.created_at, NOW()), ' hours ago')
               ELSE DATE_FORMAT(a.created_at, '%M %d, %Y')
           END as time_ago
    FROM activities a
    ORDER BY a.created_at DESC
    LIMIT 3
");
$stmt->execute();
$recent_activities = $stmt->fetchAll();
?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Patients -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex flex-row items-center justify-between pb-2">
            <h3 class="text-sm font-medium text-gray-600">Total des patients</h3>
            <i class="fas fa-users text-gray-400"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo number_format($total_patients); ?></div>
        <p class="text-xs text-gray-500">+12% par rapport au mois dernier</p>
    </div>

    <!-- Appointments Today -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex flex-row items-center justify-between pb-2">
            <h3 class="text-sm font-medium text-gray-600">Rendez-vous aujourd'hui</h3>
            <i class="fas fa-calendar text-gray-400"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo $today_appointments; ?></div>
        <p class="text-xs text-gray-500"><?php echo $remaining_appointments; ?> restant(s) pour aujourd'hui</p>
    </div>

    <!-- Average Wait Time -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex flex-row items-center justify-between pb-2">
            <h3 class="text-sm font-medium text-gray-600">Temps d'attente moyen</h3>
            <i class="fas fa-clock text-gray-400"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo $avg_wait_time; ?> min</div>
        <p class="text-xs text-gray-500">2 min de moins que la semaine dernière</p>
    </div>

    <!-- Revenue This Month -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex flex-row items-center justify-between pb-2">
            <h3 class="text-sm font-medium text-gray-600">Revenu ce mois-ci</h3>
            <i class="fas fa-dollar-sign text-gray-400"></i>
        </div>
        <div class="text-2xl font-bold"><?php echo number_format($monthly_revenue); ?> DH</div>
        <p class="text-xs text-gray-500">+8% par rapport au mois dernier</p>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Upcoming Appointments -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Rendez-vous à venir</h2>
            <p class="text-sm text-gray-500">Votre planning pour les prochaines 24 heures</p>
        </div>
        <div class="p-6 space-y-4">
            <?php if (empty($upcoming_appointments)): ?>
                <p class="text-gray-500 text-center py-4">Aucun rendez-vous à venir</p>
            <?php else: ?>
                <?php foreach ($upcoming_appointments as $appointment): ?>
                    <?php 
                        $initials = strtoupper(substr($appointment['first_name'], 0, 1) . substr($appointment['last_name'], 0, 1));
                    ?>
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-medium">
                                <?php echo $initials; ?>
                            </div>
                            <div>
                                <p class="font-medium"><?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?></p>
                                <p class="text-sm text-gray-500"><?php echo $appointment['procedure_name']; ?></p>
                                <p class="text-sm text-gray-500">
                                    <?php echo $appointment['formatted_time']; ?> (<?php echo $appointment['duration']; ?>)
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="reschedule-btn text-sm px-3 py-1 border rounded-md hover:bg-gray-50 flex items-center" 
                                    data-id="<?php echo $appointment['id']; ?>">
                                <i class="fas fa-sync-alt mr-1 text-xs"></i>
                                Replanifier
                            </button>
                            <button class="view-btn bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded-md flex items-center"
                                    data-id="<?php echo $appointment['id']; ?>">
                                <i class="fas fa-eye mr-1 text-xs"></i>
                                Voir
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Activité récente</h2>
            <p class="text-sm text-gray-500">Dernières mises à jour et notifications</p>
        </div>
        <div class="p-6 space-y-4">
            <?php if (empty($recent_activities)): ?>
                <p class="text-gray-500 text-center py-4">Aucune activité récente</p>
            <?php else: ?>
                <?php foreach ($recent_activities as $activity): ?>
                    <?php 
                        $icon_class = '';
                        switch ($activity['activity_type']) {
                            case 'patient':
                                $icon_class = 'fa-user-plus';
                                break;
                            case 'appointment':
                                $icon_class = 'fa-calendar-check';
                                break;
                            case 'payment':
                                $icon_class = 'fa-credit-card';
                                break;
                            default:
                                $icon_class = 'fa-bell';
                        }
                    ?>
                    <div class="flex items-start space-x-4 p-4 border rounded-lg">
                        <div class="mt-1">
                            <i class="fas <?php echo $icon_class; ?> text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium"><?php echo $activity['title']; ?></p>
                            <p class="text-sm text-gray-500"><?php echo $activity['description']; ?></p>
                            <p class="text-xs text-gray-400 mt-1"><?php echo $activity['time_ago']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
