<?php
/**
 * Top Navigation Layout
 * 
 * Uses a horizontal navbar at the top; content takes full width.
 */

include __DIR__ . '/../header.php';
include __DIR__ . '/topnav.php'; // the horizontal menu bar (in same folder)
?>

<div class="container-fluid mt-3">
    <?php include __DIR__ . '/../breadcrumbs.php'; ?>
    <?php echo $content_html ?? ''; ?>
</div>

<?php include __DIR__ . '/../footer.php'; ?>