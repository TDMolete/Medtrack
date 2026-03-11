<?php
/**
 * Global Footer
 * 
 * Displays full footer widgets for all pages (public and authenticated).
 * Includes Bootstrap JS and custom scripts.
 */
?>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <!-- Close the <main> tag opened in public header -->
        </main>
    <?php endif; ?>

    <!-- Footer (same for all users) -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- About Column -->
                <div class="col-md-3 mb-4">
                    <h5>About MedTrack</h5>
                    <p>A lightweight healthcare management MVP for patients and doctors. Track appointments, medical history, billing, and more.</p>
                </div>
                <!-- Quick Links -->
                <div class="col-md-2 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo BASE_URL; ?>index.php">Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>index.php#features">Features</a></li>
                        <li><a href="<?php echo BASE_URL; ?>index.php#about">About Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                <!-- Contact Info -->
                <div class="col-md-3 mb-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> support@medtrack.com</li>
                        <li><i class="fas fa-phone me-2"></i> +27 11 234 5678</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Johannesburg, South Africa</li>
                    </ul>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <!-- Newsletter & Timeline -->
                <div class="col-md-4 mb-4">
                    <h5>Newsletter</h5>
                    <p>Subscribe to get updates on new features and health tips.</p>
                    <form class="newsletter-form" id="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your email" aria-label="Email" required>
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                        <div class="form-text text-muted" id="newsletter-message"></div>
                    </form>
                    <!-- Timeline (Latest Updates) -->
                    
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 MedTrack – Healthcare Management MVP. All rights reserved.</p>
                <small class="text-muted">For portfolio and demonstration purposes only.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Theme JS (dark mode) -->
    <script src="<?php echo BASE_URL; ?>assets/js/theme.js"></script>
    <!-- AJAX helpers -->
    <script src="<?php echo BASE_URL; ?>assets/js/ajax.js"></script>
    <!-- Chart.js (only if needed – pages that include charts will load it separately) -->
    <script src="<?php echo BASE_URL; ?>assets/js/charts.js"></script>
    <!-- Export functions -->
    <script src="<?php echo BASE_URL; ?>assets/js/export.js"></script>
    <!-- General utilities -->
    <script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>

    <!-- Simple newsletter AJAX handler (dummy) -->
    <script>
    document.getElementById('newsletter-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        // Simulate subscription – in a real app, send to server
        document.getElementById('newsletter-message').innerHTML = 'Thank you for subscribing!';
        this.reset();
    });
    </script>
</body>
</html>