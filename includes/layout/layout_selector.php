<?php
/**
 * Layout Selector
 * 
 * This file should be included at the end of every page controller.
 * It uses the session variable 'layout' (default 'sidebar') to choose the wrapper.
 * 
 * Expected variables:
 *   $content_html (string) – the page content generated via ob_get_clean()
 *   $page_title    (string) – for the <title>
 *   $current_page  (string) – for active menu and breadcrumbs
 */

if (!isset($content_html)) {
    die("Layout selector error: \$content_html not set.");
}

// Get layout from session or default
$layout = $_SESSION['layout'] ?? 'sidebar';

// Allow switching via URL parameter (for demo)
if (isset($_GET['set_layout'])) {
    $layout = $_GET['set_layout'];
    $_SESSION['layout'] = $layout;
    // Remove query parameter to avoid infinite loops
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Validate layout
$allowed_layouts = ['sidebar', 'topnav', 'compact'];
if (!in_array($layout, $allowed_layouts)) {
    $layout = 'sidebar';
}

// Include the wrapper – it will use $content_html, $page_title, $current_page
include __DIR__ . "/{$layout}_layout.php";