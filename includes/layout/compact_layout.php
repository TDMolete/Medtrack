<?php
/**
 * Compact Layout
 * 
 * Uses a narrow left sidebar (icons only) to save space.
 */

include __DIR__ . '/../header.php';
include __DIR__ . '/compact_sidebar.php'; // in same folder
?>

<div class="main-content" style="margin-left: 80px; padding: 20px;">
    <?php include __DIR__ . '/../breadcrumbs.php'; ?>
    <?php echo $content_html ?? ''; ?>
</div>

<?php include __DIR__ . '/../footer.php'; ?>