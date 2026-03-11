<?php
/**
 * Top Navigation Layout
 * 
 * Uses a horizontal navbar at the top; content takes full width.
 */

include 'includes/header.php';
include 'includes/layout/topnav.php'; // the horizontal menu bar
?>

<div class="container-fluid mt-3">
    <?php include 'includes/breadcrumbs.php'; ?>
    <?php echo $content_html; ?>
</div>

<?php include 'includes/footer.php'; ?>