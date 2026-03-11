<?php
/**
 * Doctor Sidebar (full width)
 */

$nav_items = [
    'doctor_dashboard'   => ['label' => 'Dashboard',    'icon' => 'fa-speedometer',   'url' => '/medtrack/pages/doctor/dashboard.php'],
    'patient_list'       => ['label' => 'My Patients',  'icon' => 'fa-users',         'url' => '/medtrack/pages/doctor/patient_list.php'],
    'doctor_appointments'=> ['label' => 'Appointments', 'icon' => 'fa-calendar-check', 'url' => '/medtrack/pages/doctor/appointments.php'],
    'doctor_history'     => ['label' => 'Records',      'icon' => 'fa-file-medical',  'url' => '/medtrack/pages/doctor/history.php'],
];

$current_page = $current_page ?? 'doctor_dashboard';
?>
<div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 250px; height: 100vh; position: fixed; left: 0; top: 0;">
    <h4 class="text-primary">MedTrack <small class="text-muted">(Doctor)</small></h4>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <?php foreach ($nav_items as $key => $item): ?>
        <li class="nav-item">
            <a href="<?php echo $item['url']; ?>" class="nav-link <?php echo ($current_page == $key) ? 'active' : ''; ?>">
                <i class="fas <?php echo $item['icon']; ?> me-2"></i>
                <?php echo $item['label']; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-user-md me-2 fs-4"></i>
            <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'Doctor'); ?></strong>
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" onclick="toggleTheme()">Toggle Dark Mode</a></li>
            <li><a class="dropdown-item" href="/medtrack/auth/logout.php">Sign out</a></li>
        </ul>
    </div>
</div>