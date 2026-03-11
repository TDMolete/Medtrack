<?php
/**
 * Breadcrumbs
 * 
 * Expects $current_page to be set (e.g., 'dashboard', 'profile', etc.)
 * Map each page to a display name.
 */

if (!isset($current_page)) {
    return; // no breadcrumbs if page not defined
}

$breadcrumb_map = [
    'dashboard'          => 'Dashboard',
    'profile'            => 'Profile',
    'medical_history'    => 'Medical History',
    'appointments'       => 'Appointments',
    'billing'            => 'Billing',
    'medications'        => 'Medications',
    'recommendations'    => 'Health Tips',
    'patient_list'       => 'Patients',
    'doctor_appointments'=> 'Appointments',
    'doctor_history'     => 'Patient History',
];

$home_link = isPatient() ? '/medtrack/pages/patient/dashboard.php' 
            : (isDoctor() ? '/medtrack/pages/doctor/dashboard.php' 
            : '/medtrack/index.php');
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo $home_link; ?>"><i class="fas fa-home"></i> Home</a></li>
        <?php if (isset($breadcrumb_map[$current_page])): ?>
        <li class="breadcrumb-item active" aria-current="page">
            <?php echo $breadcrumb_map[$current_page]; ?>
        </li>
        <?php endif; ?>
    </ol>
</nav>