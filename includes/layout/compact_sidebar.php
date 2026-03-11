<?php
/**
 * Compact Sidebar (icons only)
 */
$nav_items = [
    'dashboard'        => ['icon' => 'fa-speedometer',   'url' => '/medtrack/pages/patient/dashboard.php'],
    'profile'          => ['icon' => 'fa-user',          'url' => '/medtrack/pages/patient/profile.php'],
    'medical_history'  => ['icon' => 'fa-file-medical',  'url' => '/medtrack/pages/patient/medical_history.php'],
    'appointments'     => ['icon' => 'fa-calendar-check', 'url' => '/medtrack/pages/patient/appointments.php'],
    'billing'          => ['icon' => 'fa-cash-coin',     'url' => '/medtrack/pages/patient/billing.php'],
    'medications'      => ['icon' => 'fa-capsule',       'url' => '/medtrack/pages/patient/medications.php'],
    'recommendations'  => ['icon' => 'fa-heart',         'url' => '/medtrack/pages/patient/recommendations.php'],
];
$current_page = $current_page ?? 'dashboard';
?>
<div class="d-flex flex-column flex-shrink-0 p-2 bg-light" style="width: 80px; height: 100vh; position: fixed; left: 0; top: 0;">
    <h5 class="text-center text-primary">M</h5>
    <hr>
    <ul class="nav nav-pills flex-column text-center">
        <?php foreach ($nav_items as $key => $item): ?>
        <li class="nav-item mb-2">
            <a href="<?php echo $item['url']; ?>" class="nav-link <?php echo ($current_page == $key) ? 'active' : ''; ?>" title="<?php echo ucfirst($key); ?>">
                <i class="fas <?php echo $item['icon']; ?> fa-lg"></i>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <hr>
    <div class="text-center">
        <a href="#" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle fa-2x"></i>
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" onclick="toggleTheme()">Dark Mode</a></li>
            <li><a class="dropdown-item" href="/medtrack/auth/logout.php">Logout</a></li>
        </ul>
    </div>
</div>