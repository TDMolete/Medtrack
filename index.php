<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedTrack – Advanced Healthcare MVP</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS (Animate on Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Swiper (testimonials carousel) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background: #f8fafc;
            color: #1e293b;
        }
        /* 3D canvas background */
        #canvas-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.15;
            pointer-events: none;
        }
        /* Navbar */
        .navbar {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        .navbar-brand {
            font-weight: 700;
            color: #0d6efd;
        }
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px #0d6efd;
        }
        /* Hero */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 6rem 1rem;
            position: relative;
            overflow: hidden;
        }
        .hero h1 {
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 800;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease;
        }
        .hero .lead {
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto 2.5rem;
            color: #475569;
            animation: fadeInUp 1.2s ease;
        }
        .hero-btns {
            animation: fadeInUp 1.4s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Stats counter */
        .stats-section {
            background: white;
            padding: 4rem 0;
            box-shadow: 0 -20px 30px -20px rgba(0,0,0,0.1);
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #0d6efd;
        }
        .stat-label {
            font-size: 1.1rem;
            color: #64748b;
        }
        /* Features */
        .features-section {
            padding: 5rem 0;
        }
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            border: 1px solid #e2e8f0;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px -15px #0d6efd80;
            border-color: #0d6efd;
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 1.2rem;
        }
        /* Story timeline */
        .timeline {
            position: relative;
            padding: 2rem 0;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            width: 2px;
            height: 100%;
            background: #0d6efd;
            transform: translateX(-50%);
        }
        .timeline-item {
            position: relative;
            margin-bottom: 3rem;
            width: 50%;
            padding: 1rem 2rem;
        }
        .timeline-item:nth-child(odd) {
            left: 0;
            text-align: right;
        }
        .timeline-item:nth-child(even) {
            left: 50%;
        }
        .timeline-item .badge {
            background: #0d6efd;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .timeline::before { left: 30px; }
            .timeline-item { width: 100%; left: 0 !important; padding-left: 70px; text-align: left; }
        }
        /* Testimonials */
        .testimonials-section {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            padding: 5rem 0;
        }
        .swiper {
            padding: 2rem 0 4rem;
        }
        .testimonial-card {
            background: white;
            border-radius: 2rem;
            padding: 2rem;
            box-shadow: 0 15px 30px -10px rgba(0,0,0,0.1);
            margin: 0 1rem;
            text-align: center;
        }
        .testimonial-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 3px solid #0d6efd;
        }
        .testimonial-card h5 {
            font-weight: 600;
        }
        .testimonial-card .position {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .testimonial-card .stars {
            color: #ffc107;
            margin-bottom: 1rem;
        }
        /* Reviews grid */
        .reviews-section {
            padding: 5rem 0;
            background: white;
        }
        .review-card {
            background: #f8fafc;
            border-radius: 1rem;
            padding: 1.5rem;
            border-left: 4px solid #0d6efd;
            margin-bottom: 1.5rem;
        }
        /* Contact */
        .contact-section {
            padding: 5rem 0;
            background: #1e293b;
            color: white;
        }
        .contact-section input,
        .contact-section textarea {
            background: #334155;
            border: none;
            color: white;
        }
        .contact-section input::placeholder,
        .contact-section textarea::placeholder {
            color: #94a3b8;
        }
        .contact-info i {
            color: #0d6efd;
            margin-right: 10px;
        }
        /* Footer styles from previous version */
        .footer {
            background: #0f172a;
            color: #e2e8f0;
        }
        .footer a { color: #94a3b8; }
        .footer a:hover { color: white; }
        .timeline-item .timeline-date { color: #0d6efd; font-weight: 600; }
    </style>
</head>
<body>
    <!-- 3D Canvas Background -->
    <div id="canvas-container"></div>

    <!-- Navbar (same as before) -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-heartbeat text-primary me-2"></i>MedTrack
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#story">Story</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                    <li class="nav-item"><a class="nav-link" href="#reviews">Reviews</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="auth/login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white px-3" href="auth/register.php">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 data-aos="fade-up">Your Health,<br>Our Priority</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="200">MedTrack is a next‑gen healthcare MVP. Track appointments, medical history, billing, and medications – all with cutting‑edge technology.</p>
            <div class="hero-btns" data-aos="fade-up" data-aos-delay="400">
                <a href="auth/register.php" class="btn btn-primary btn-lg me-3"><i class="fas fa-user-plus me-2"></i>Get Started</a>
                <a href="#features" class="btn btn-outline-primary btn-lg">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Stats Counter -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number counter" data-target="5000">0</div>
                    <div class="stat-label">Active Patients</div>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number counter" data-target="12000">0</div>
                    <div class="stat-label">Appointments</div>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number counter" data-target="250">0</div>
                    <div class="stat-label">Doctors</div>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number counter" data-target="98">0%</div>
                    <div class="stat-label">Satisfaction</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Designed for Modern Healthcare</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-notes-medical"></i></div>
                        <h4>Medical History</h4>
                        <p>Securely store past illnesses, treatments, and doctor notes. Export as PDF/CSV.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                        <h4>Appointments</h4>
                        <p>Book, reschedule, and cancel with ease. Get reminders before your visit.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <h4>Billing & VAT</h4>
                        <p>Automatic 7.5% VAT calculation, payment tracking, and invoice generation.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-pills"></i></div>
                        <h4>Medication Reminders</h4>
                        <p>Never miss a dose with custom schedules and push notifications.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                        <h4>Health Trends</h4>
                        <p>Visualise blood pressure, glucose, and weight trends with interactive charts.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                        <h4>Secure & Private</h4>
                        <p>End‑to‑end encryption, GDPR compliant, and role‑based access control.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Story / About Section (Timeline) -->
    <section id="story" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Our Journey</h2>
            <div class="timeline">
                <div class="timeline-item" data-aos="fade-right">
                    <span class="badge">2023</span>
                    <h5>Idea Born</h5>
                    <p>After seeing gaps in local clinics, we started sketching the MVP.</p>
                </div>
                <div class="timeline-item" data-aos="fade-left">
                    <span class="badge">2024</span>
                    <h5>First Prototype</h5>
                    <p>Built with PHP/MySQL – patient authentication, medical history, billing.</p>
                </div>
                <div class="timeline-item" data-aos="fade-right">
                    <span class="badge">Early 2025</span>
                    <h5>Doctor Portal</h5>
                    <p>Added doctor dashboards, appointment management, and patient records.</p>
                </div>
                <div class="timeline-item" data-aos="fade-left">
                    <span class="badge">Today</span>
                    <h5>Advanced MVP</h5>
                    <p>Dark mode, charts, export, real‑time notifications, and 3D visuals.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Carousel -->
    <section id="testimonials" class="testimonials-section">
        <div class="container">
            <h2 class="text-center mb-4" data-aos="fade-up">What Our Users Say</h2>
            <div class="swiper testimonial-swiper" data-aos="fade-up">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User">
                            <h5>Sarah M.</h5>
                            <div class="position">Patient</div>
                            <div class="stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p>"MedTrack made managing my chronic condition so much easier. The medication reminders are a lifesaver!"</p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User">
                            <h5>Dr. James K.</h5>
                            <div class="position">Doctor</div>
                            <div class="stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            </div>
                            <p>"I can quickly access my patients' history and upcoming appointments. It saves me hours each week."</p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="User">
                            <h5>Linda R.</h5>
                            <div class="position">Patient</div>
                            <div class="stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p>"The billing feature is fantastic – I can see all my invoices and VAT breakdown at a glance."</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </section>

    <!-- Reviews Grid -->
    <section id="reviews" class="reviews-section">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Recent Reviews</h2>
            <div class="row">
                <div class="col-md-6" data-aos="fade-right">
                    <div class="review-card">
                        <div class="d-flex justify-content-between">
                            <h5>John Doe</h5>
                            <span class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                        </div>
                        <p class="text-muted">“Absolutely love the new dark mode and the chart features. Makes tracking my BP fun!”</p>
                        <small>2 days ago</small>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <div class="review-card">
                        <div class="d-flex justify-content-between">
                            <h5>Dr. Ndlovu</h5>
                            <span class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                        </div>
                        <p class="text-muted">“The patient list and history view are very intuitive. Highly recommended for any practice.”</p>
                        <small>1 week ago</small>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-right">
                    <div class="review-card">
                        <div class="d-flex justify-content-between">
                            <h5>Mary S.</h5>
                            <span class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                        </div>
                        <p class="text-muted">“The export to PDF saved me when I needed to show my history to a specialist. Great feature!”</p>
                        <small>2 weeks ago</small>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <div class="review-card">
                        <div class="d-flex justify-content-between">
                            <h5>Clinic Care</h5>
                            <span class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></span>
                        </div>
                        <p class="text-muted">“We use MedTrack in our small clinic. It's reliable and easy to train new staff.”</p>
                        <small>3 weeks ago</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4" data-aos="fade-right">
                    <h2>Get in Touch</h2>
                    <p class="lead">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                    <div class="contact-info">
                        <p><i class="fas fa-envelope"></i> support@medtrack.com</p>
                        <p><i class="fas fa-phone"></i> +27 11 234 5678</p>
                        <p><i class="fas fa-map-marker-alt"></i> Johannesburg, South Africa</p>
                    </div>
                    <div class="social-icons mt-4">
                        <a href="#" class="me-3 text-white"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="me-3 text-white"><i class="fab fa-twitter fa-2x"></i></a>
                        <a href="#" class="me-3 text-white"><i class="fab fa-linkedin fa-2x"></i></a>
                        <a href="#" class="me-3 text-white"><i class="fab fa-github fa-2x"></i></a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <form id="contact-form">
                        <div class="mb-3">
                            <input type="text" class="form-control form-control-lg" placeholder="Your Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control form-control-lg" placeholder="Email Address" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control form-control-lg" rows="5" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (from previous version, slightly modified) -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h5>About MedTrack</h5>
                    <p>A lightweight healthcare management MVP for patients and doctors. Track appointments, medical history, billing, and more.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#story">Story</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> support@medtrack.com</li>
                        <li><i class="fas fa-phone me-2"></i> +27 11 234 5678</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Johannesburg, South Africa</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Newsletter</h5>
                    <p>Subscribe for updates and health tips.</p>
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your email">
                            <button class="btn btn-primary" type="button">Subscribe</button>
                        </div>
                    </form>
                    <div class="mt-4">
                        <h5>Latest Updates</h5>
                        <div class="timeline-item">
                            <div class="timeline-date">15 Apr 2025</div>
                            <div>New chart visualisation for blood pressure.</div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-date">10 Apr 2025</div>
                            <div>Export medical history as PDF now available.</div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 MedTrack – Healthcare Management MVP. All rights reserved.</p>
                <small class="text-muted">For portfolio and demonstration purposes only.</small>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Three.js 3D Background (rotating medical cross / DNA helix)
        const container = document.getElementById('canvas-container');
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setClearColor(0x000000, 0); // transparent
        container.appendChild(renderer.domElement);

        // Create a group of floating cubes (representing data)
        const geometry = new THREE.TorusKnotGeometry(1, 0.3, 100, 16);
        const material = new THREE.MeshStandardMaterial({ color: 0x0d6efd, emissive: 0x0d6efd, emissiveIntensity: 0.5 });
        const torusKnot = new THREE.Mesh(geometry, material);
        scene.add(torusKnot);

        // Add some floating particles
        const particlesGeo = new THREE.BufferGeometry();
        const particlesCount = 1000;
        const posArray = new Float32Array(particlesCount * 3);
        for(let i = 0; i < particlesCount * 3; i += 3) {
            posArray[i] = (Math.random() - 0.5) * 20;
            posArray[i+1] = (Math.random() - 0.5) * 20;
            posArray[i+2] = (Math.random() - 0.5) * 20;
        }
        particlesGeo.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        const particlesMat = new THREE.PointsMaterial({ size: 0.05, color: 0x0d6efd, transparent: true, opacity: 0.6 });
        const particlesMesh = new THREE.Points(particlesGeo, particlesMat);
        scene.add(particlesMesh);

        // Lights
        const light = new THREE.DirectionalLight(0xffffff, 1);
        light.position.set(2, 2, 5);
        scene.add(light);
        const ambientLight = new THREE.AmbientLight(0x404060);
        scene.add(ambientLight);

        camera.position.z = 8;

        function animate() {
            requestAnimationFrame(animate);
            torusKnot.rotation.x += 0.005;
            torusKnot.rotation.y += 0.01;
            particlesMesh.rotation.y += 0.0005;
            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        // CountUp effect for stats
        const counters = document.querySelectorAll('.counter');
        const speed = 200;

        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText.replace(/[^0-9]/g, '');
                const inc = Math.ceil(target / 15);
                if (count < target) {
                    counter.innerText = count + inc;
                    setTimeout(updateCount, 40);
                } else {
                    counter.innerText = target + (counter.innerText.includes('%') ? '%' : '');
                }
            };
            updateCount();
        });

        // Swiper initialization
        new Swiper('.testimonial-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: { delay: 5000 },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            breakpoints: {
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 }
            }
        });

        // Contact form simulation
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your message. We will get back to you soon!');
            this.reset();
        });
    </script>
</body>
</html>