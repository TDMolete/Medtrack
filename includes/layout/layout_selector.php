<?php
// Determine layout from session, default 'sidebar'
$layout = $_SESSION['layout'] ?? 'sidebar';
if (isset($_GET['set_layout'])) {
    $layout = $_GET['set_layout'];
    $_SESSION['layout'] = $layout;
    // Redirect to remove query string
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}
// Include the appropriate layout wrapper
include "includes/layout/{$layout}_layout.php";