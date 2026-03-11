<?php
/**
 * Sidebar Layout
 * 
 * Uses a fixed left sidebar and main content area on the right.
 */

// Use __DIR__ to build absolute paths
include __DIR__ . '/../header.php';

// Include the appropriate role‑based sidebar (located in same folder)
if (isPatient()) {
    include __DIR__ . '/patient_sidebar.php';
} elseif (isDoctor()) {
    include __DIR__ . '/doctor_sidebar.php';
} else {
    include __DIR__ . '/patient_sidebar.php'; // fallback
}
?>

<!-- Main Content Area -->
<div class="main-content" style="margin-left: 250px; padding: 20px;">
    <?php include __DIR__ . '/../breadcrumbs.php'; ?>
    <?php echo $content_html ?? ''; ?>
</div>

<?php include __DIR__ . '/../footer.php'; ?>