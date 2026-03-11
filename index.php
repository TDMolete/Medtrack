<?php
/**
 * MedTrack – Landing Page
 * 
 * If user is already logged in, redirect to their dashboard.
 * Otherwise display a professional landing page with hero, features, and call‑to‑action.
 */

session_start();

// If logged in, redirect based on role
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'] ?? 'patient';
    switch ($role) {
        case 'doctor':
            header("Location: pages/doctor/dashboard.php");
            break;
        case 'admin':
            header("Location: pages/admin/dashboard.php");
            break;
        case 'patient':
        default:
            header("Location: pages/patient/dashboard.php");
            break;
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedTrack – Healthcare Management MVP</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecf2 100%);
            color: #1e293b;
            line-height: 1.6;
        }
        .navbar {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
        }
        .hero {
            padding: 6rem 1rem;
            text-align: center;
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 0 0 3rem 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 1.5rem;
        }
        .hero .lead {
            font-size: 1.35rem;
            max-width: 700px;
            margin: 0 auto 2.5rem;
            color: #475569;
        }
        .btn-custom {
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(13,110,253,0.2);
        }
        .features {
            padding: 5rem 1rem;
        }
        .features h2 {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 3rem;
        }
        .feature-card {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            text-align: center;
            height: 100%;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px -10px rgba(0,0,0,0.1);
            border-color: #0d6efd;
        }
        .feature-icon {
            font-size: 2.8rem;
            color: #0d6efd;
            margin-bottom: 1.5rem;
        }
        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .feature-card p {
            color: #64748b;
        }
        .footer {
            background: #1e293b;
            color: white;
            padding: 2rem 1rem;
            text-align: center;
            border-radius: 3rem 3rem 0 0;
        }
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-heartbeat text-primary me-2"></i>MedTrack
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="auth/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-4" href="auth/register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Your Health, Simplified</h1>
            <p class="lead">MedTrack is a lightweight healthcare management MVP. Track appointments, medical history, billing, and medications – all in one secure place.</p>
            <a href="auth/register.php" class="btn btn-primary btn-custom me-3">
                <i class="fas fa-user-plus me-2"></i>Get Started Free
            </a>
            <a href="auth/login.php" class="btn btn-outline-primary btn-custom">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2>Everything you need, nothing you don't</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-notes-medical"></i></div>
                        <h3>Medical History</h3>
                        <p>Record past illnesses, doctor notes, and treatments. Export as PDF or CSV.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                        <h3>Appointments</h3>
                        <p>Book, view, and cancel appointments with ease. Upcoming appointments at a glance.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <h3>Billing & VAT</h3>
                        <p>Automatic 7.5% VAT calculation, payment tracking, and invoice history.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-pills"></i></div>
                        <h3>Medication Reminders</h3>
                        <p>Track dosages, frequencies, and schedules. Never miss a dose.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-heart"></i></div>
                        <h3>Health Tips</h3>
                        <p>General wellness advice – personalised tips coming soon.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                        <h3>Trends & Charts</h3>
                        <p>Visualise blood pressure trends with interactive charts.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-2">&copy; 2025 MedTrack – Healthcare Management MVP. Built by Tumisang David Molete.</p>
            <p class="small text-muted">For portfolio and demonstration purposes only. Not for clinical use.</p>
        </div>
    </footer>

    <!-- Bootstrap JS (optional for any interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>