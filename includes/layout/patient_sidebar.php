<?php
/**
 * Patient Sidebar (full width)
 */

$nav_items = [
    'dashboard'        => ['label' => 'Dashboard',       'icon' => 'fa-speedometer',   'url' => '/medtrack/pages/patient/dashboard.php'],
    'profile'          => ['label' => 'Profile',         'icon' => 'fa-user',          'url' => '/medtrack/pages/patient/profile.php'],
    'medical_history'  => ['label' => 'Medical History', 'icon' => 'fa-file-medical',  'url' => '/medtrack/pages/patient/medical_history.php'],
    'appointments'     => ['label' => 'Appointments',    'icon' => 'fa-calendar-check', 'url' => '/medtrack/pages/patient/appointments.php'],
    'billing'          => ['label' => 'Billing',         'icon' => 'fa-cash-coin',     'url' => '/medtrack/pages/patient/billing.php'],
    'medications'      => ['label' => 'Medications',     'icon' => 'fa-capsule',       'url' => '/medtrack/pages/patient/medications.php'],
    'recommendations'  => ['label' => 'Health Tips',     'icon' => 'fa-heart',         'url' => '/medtrack/pages/patient/recommendations.php'],
];

// Determine active page (set by controller)
$current_page = $current_page ?? 'dashboard';
?>
<!-- Fixed sidebar -->
<div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 250px; height: 100vh; position: fixed; left: 0; top: 0;">
    <h4 class="text-primary">MedTrack</h4>
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
            <i class="fas fa-user-circle me-2 fs-4"></i>
            <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></strong>
            <span class="badge bg-danger ms-2" id="notif-badge" style="display: none;">0</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/medtrack/pages/patient/profile.php">Settings</a></li>
            <li><a class="dropdown-item" href="#" onclick="toggleTheme()">Toggle Dark Mode</a></li>
            <li><a class="dropdown-item" href="/medtrack/auth/logout.php">Sign out</a></li>
        </ul>
    </div>
</div>