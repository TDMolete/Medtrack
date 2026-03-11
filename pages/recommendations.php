<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: /medtrack/auth/login.php"); exit; }
include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="main-content p-4" style="margin-left: 250px;">
    <h2>Exercise & Health Tips</h2>
    <p>Based on your profile, here are some general recommendations (MVP version – static content).</p>

    <div class="row mt-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Stay Active</h5>
                    <p class="card-text">Aim for at least 30 minutes of moderate exercise daily. Walking, cycling, or swimming are great options.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Hydration</h5>
                    <p class="card-text">Drink plenty of water – about 8 glasses a day – to maintain energy and focus.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Balanced Diet</h5>
                    <p class="card-text">Include fruits, vegetables, lean proteins, and whole grains in your meals.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sleep Well</h5>
                    <p class="card-text">Adults need 7-9 hours of quality sleep per night for optimal health.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Future: personalized recommendations based on medical history -->
</div>
<?php include '../includes/footer.php'; ?>