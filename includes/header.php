<?php
/**
 * Global Header
 * 
 * Expected variables:
 *   $page_title (string) – optional, defaults to "MedTrack"
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = $page_title ?? "MedTrack";
$current_page = $current_page ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <!-- Bootstrap 5 (light mode) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- jsPDF & AutoTable (for exports) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
    
    <!-- Custom CSS (base styles) -->
    <link rel="stylesheet" href="/medtrack/assets/css/style.css">
    
    <!-- Dark mode CSS (disabled by default) -->
    <link rel="stylesheet" href="/medtrack/assets/css/dark.css" id="dark-theme" disabled>
    
    <!-- Layout-specific CSS -->
    <link rel="stylesheet" href="/medtrack/assets/css/layouts.css">
</head>
<body>
    <!-- The sidebar/topnav will be included separately by layout wrappers -->