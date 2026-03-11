<?php
/**
 * Compact Layout
 * 
 * Uses a narrow left sidebar (icons only) to save space.
 */

include 'includes/header.php';
include 'includes/layout/compact_sidebar.php';
?>

<div class="main-content" style="margin-left: 80px; padding: 20px;">
    <?php include 'includes/breadcrumbs.php'; ?>
    <?php echo $content_html; ?>
</div>

<?php include 'includes/footer.php'; ?>