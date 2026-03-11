<?php
include 'includes/header.php';
include 'includes/layout/compact_sidebar.php'; // slim sidebar
?>
<div class="main-content" style="margin-left: 80px; padding: 20px;">
    <?php include 'includes/breadcrumbs.php'; ?>
    <?php include $content_page; ?>
</div>
<?php include 'includes/footer.php'; ?>