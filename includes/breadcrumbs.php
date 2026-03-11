<?php
$breadcrumb_map = [
    'dashboard' => 'Dashboard',
    'profile' => 'Profile',
    'medical_history' => 'Medical History',
    'appointments' => 'Appointments',
    'billing' => 'Billing',
    'medications' => 'Medications',
    'recommendations' => 'Health Tips',
    'patient_list' => 'Patients',
];
$current = $current_page ?? '';
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo isPatient() ? 'dashboard.php' : '../doctor/dashboard.php'; ?>">Home</a></li>
        <?php if ($current && isset($breadcrumb_map[$current])): ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $breadcrumb_map[$current]; ?></li>
        <?php endif; ?>
    </ol>
</nav>