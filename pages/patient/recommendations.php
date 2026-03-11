<?php
require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isPatient()) { header("Location: /medtrack/unauthorized.php"); exit; }

$user_id = $_SESSION['user_id'];
$patient_id = getPatientIdByUserId($pdo, $user_id);

// Optional: fetch patient data to personalise recommendations
$stmt = $pdo->prepare("SELECT date_of_birth, gender FROM patients WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();

ob_start();
?>
<div class="container-fluid">
    <h2>Exercise & Health Tips</h2>
    <p>Based on general guidelines. Future versions may personalise these tips based on your medical history.</p>

    <div class="row mt-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-heartbeat text-danger"></i> Stay Active</h5>
                    <p class="card-text">Aim for at least 30 minutes of moderate exercise daily. Walking, cycling, or swimming are great options.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-tint text-primary"></i> Hydration</h5>
                    <p class="card-text">Drink plenty of water – about 8 glasses a day – to maintain energy and focus.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-apple-alt text-success"></i> Balanced Diet</h5>
                    <p class="card-text">Include fruits, vegetables, lean proteins, and whole grains in your meals.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-moon text-warning"></i> Sleep Well</h5>
                    <p class="card-text">Adults need 7-9 hours of quality sleep per night for optimal health.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-smoking-ban text-secondary"></i> Avoid Smoking & Excess Alcohol</h5>
                    <p class="card-text">Reduce risk of chronic diseases by limiting alcohol and avoiding tobacco.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-calendar-check text-info"></i> Regular Checkups</h5>
                    <p class="card-text">Schedule annual physicals and keep up with vaccinations.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content_html = ob_get_clean();
$page_title = "Health Tips";
$current_page = 'recommendations';
include '../../includes/layout/layout_selector.php';
?>