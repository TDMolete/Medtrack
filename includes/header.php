<?php
/**
 * Global Header
 * 
 * For public pages: displays full navbar with links.
 * For authenticated pages: only outputs <head> and opening <body> (sidebar provides navigation).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logged_in = isset($_SESSION['user_id']);
$page_title = $page_title ?? 'MedTrack – Healthcare MVP';
$current_page = $current_page ?? '';

// Determine base URL (adjust if your project is not at /medtrack/)
define('BASE_URL', '/medtrack/'); // Change if needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/dark.css" id="dark-theme" disabled>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/layouts.css">
    <!-- Chart.js, jsPDF etc. are loaded only on pages that need them (via footer) -->
</head>
<body>
<?php if (!$logged_in): ?>
    <!-- Public Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php">
                <i class="fas fa-heartbeat text-primary me-2"></i>MedTrack
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublic">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarPublic">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>auth/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3" href="<?php echo BASE_URL; ?>auth/register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Main content wrapper for public pages -->
    <main>
<?php endif; ?>
<!-- For authenticated users, the sidebar_layout will include its own content area -->