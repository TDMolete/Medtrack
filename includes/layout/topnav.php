<?php
/**
 * Top Navigation Bar (for topnav layout)
 */
$current_page = $current_page ?? 'dashboard';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">MedTrack</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (isPatient()): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page=='dashboard')?'active':''; ?>" href="/medtrack/pages/patient/dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page=='profile')?'active':''; ?>" href="/medtrack/pages/patient/profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page=='medical_history')?'active':''; ?>" href="/medtrack/pages/patient/medical_history.php">Medical History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page=='appointments')?'active':''; ?>" href="/medtrack/pages/patient/appointments.php">Appointments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page=='billing')?'active':''; ?>" href="/medtrack/pages/patient/billing.php">Billing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page=='medications')?'active':''; ?>" href="/medtrack/pages/patient/medications.php">Medications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page=='recommendations')?'active':''; ?>" href="/medtrack/pages/patient/recommendations.php">Health Tips</a>
                </li>
                <?php elseif (isDoctor()): ?>
                <!-- doctor nav items -->
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page=='doctor_dashboard')?'active':''; ?>" href="/medtrack/pages/doctor/dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page=='patient_list')?'active':''; ?>" href="/medtrack/pages/doctor/patient_list.php">Patients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page=='doctor_appointments')?'active':''; ?>" href="/medtrack/pages/doctor/appointments.php">Appointments</a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
                        <span class="badge bg-danger" id="notif-badge" style="display: none;">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" onclick="toggleTheme()">Toggle Dark Mode</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/medtrack/auth/logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>