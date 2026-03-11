<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Redirect if not logged in (extra safety)
if (!isset($_SESSION['user_id'])) {
    header("Location: /medtrack/auth/login.php");
    exit;
}
$username = $_SESSION['username'] ?? 'User';

// Navigation items array (icon classes from Font Awesome)
$nav_items = [
    'dashboard'        => ['label' => 'Dashboard',       'icon' => 'fa-speedometer',   'url' => 'dashboard.php'],
    'profile'          => ['label' => 'Profile',         'icon' => 'fa-user',          'url' => 'profile.php'],
    'medical_history'  => ['label' => 'Medical History', 'icon' => 'fa-file-medical',  'url' => 'medical_history.php'],
    'appointments'     => ['label' => 'Appointments',    'icon' => 'fa-calendar-check', 'url' => 'appointments.php'],
    'billing'          => ['label' => 'Billing',         'icon' => 'fa-cash-coin',     'url' => 'billing.php'],
    'medications'      => ['label' => 'Medications',     'icon' => 'fa-capsule',       'url' => 'medications.php'],
    'recommendations'  => ['label' => 'Health Tips',     'icon' => 'fa-heart',         'url' => 'recommendations.php'],
];

// Determine current page (set in each page before including sidebar)
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
            <strong><?php echo htmlspecialchars($username); ?></strong>
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="profile.php">Settings</a></li>
            <li><a class="dropdown-item" href="/medtrack/auth/logout.php">Sign out</a></li>
        </ul>
    </div>
</div>