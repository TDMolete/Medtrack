<?php
// Start session to check if user is already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    // If logged in, redirect to dashboard
    header("Location: pages/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedTrack - Healthcare Management MVP</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Additional landing page styles */
        .hero {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: white;
            padding: 4rem 2rem;
            border-radius: 0 0 2rem 2rem;
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #0d6efd;
        }
        .card {
            border: none;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .btn-outline-light:hover {
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Simple navigation for landing page -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">MedTrack</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="auth/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3" href="auth/register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero section -->
    <div class="hero text-center">
        <h1 class="display-4 fw-bold">Welcome to MedTrack</h1>
        <p class="lead mb-4">A lightweight healthcare management prototype – built with the MVP mindset.</p>
        <a href="auth/register.php" class="btn btn-light btn-lg me-2">Get Started</a>
        <a href="auth/login.php" class="btn btn-outline-light btn-lg">Login</a>
    </div>

    <!-- Features section -->
    <div class="container my-5">
        <h2 class="text-center mb-5">Core Features</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <div class="feature-icon mb-3">👤</div>
                    <h5>Patient Dashboard</h5>
                    <p class="text-muted">Overview of appointments, bills, and medications at a glance.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <div class="feature-icon mb-3">📋</div>
                    <h5>Medical History</h5>
                    <p class="text-muted">Record and track past illnesses, treatments, and doctor notes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <div class="feature-icon mb-3">📅</div>
                    <h5>Appointments</h5>
                    <p class="text-muted">Book, view, and cancel appointments with ease.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <div class="feature-icon mb-3">💰</div>
                    <h5>Billing & VAT</h5>
                    <p class="text-muted">Automatic 7.5% VAT calculation and payment tracking.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <div class="feature-icon mb-3">💊</div>
                    <h5>Medication Reminders</h5>
                    <p class="text-muted">Keep track of dosages and schedules.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <div class="feature-icon mb-3">🔐</div>
                    <h5>Secure Authentication</h5>
                    <p class="text-muted">Passwords hashed, sessions managed, SQL injection protected.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- About MVP section -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3>Built with the MVP philosophy</h3>
                    <p class="text-muted">MedTrack started as a simple idea: help patients manage health information digitally. Instead of building everything at once, I focused on the core features you see here – a lean, functional prototype that solves real problems. Future versions could add doctor portals, email reminders, and more.</p>
                    <p class="text-muted">This project showcases my approach to software development: start small, learn fast, and iterate.</p>
                </div>
                <div class="col-md-6 text-center">
                    <i class="fas fa-rocket" style="font-size: 5rem; color: #0d6efd;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to action -->
    <div class="container text-center my-5">
        <h3>Ready to take control of your health records?</h3>
        <a href="auth/register.php" class="btn btn-primary btn-lg mt-3">Create Free Account</a>
    </div>

    <!-- Footer (similar to includes/footer.php but without extra scripts) -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p class="mb-0">MedTrack – Healthcare Management MVP &copy; 2025 Tumisang David Molete</p>
        <small class="text-muted">For portfolio and demonstration purposes only.</small>
    </footer>

    <!-- Bootstrap JS bundle (optional for any interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
</body>
</html>