<?php
/**
 * Sidebar Layout
 * 
 * Uses a fixed left sidebar and main content area on the right.
 */

// Include header (opens <body>)
include 'includes/header.php';

// Include the appropriate role‑based sidebar
if (isPatient()) {
    include 'includes/layout/patient_sidebar.php';
} elseif (isDoctor()) {
    include 'includes/layout/doctor_sidebar.php';
} else {
    // Fallback (e.g., admin) – you can create an admin sidebar later
    include 'includes/layout/patient_sidebar.php';
}
?>

<!-- Main Content Area -->
<div class="main-content" style="margin-left: 250px; padding: 20px;">
    <?php include 'includes/breadcrumbs.php'; ?>
    <?php echo $content_html; ?>
</div>

<?php include 'includes/footer.php'; ?>