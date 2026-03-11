<?php
// This layout uses fixed left sidebar
include 'includes/header.php';
include 'includes/layout/sidebar.php'; // role‑based sidebar
?>
<div class="main-content" style="margin-left: 250px; padding: 20px;">
    <?php include 'includes/breadcrumbs.php'; ?>
    <?php include $content_page; ?>
</div>
<?php include 'includes/footer.php'; ?>