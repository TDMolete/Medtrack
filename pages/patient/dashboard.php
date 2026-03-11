<?php
require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isPatient()) { header("Location: /medtrack/unauthorized.php"); exit; }

$user_id = $_SESSION['user_id'];
$patient_id = getPatientIdByUserId($pdo, $user_id);

// Fetch stats
$stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE patient_id = ? AND appointment_date >= CURDATE() AND status='scheduled'");
$stmt->execute([$patient_id]);
$upcoming_appointments = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM bills WHERE patient_id = ? AND status='unpaid'");
$stmt->execute([$patient_id]);
$unpaid_bills = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM medications WHERE patient_id = ? AND end_date >= CURDATE()");
$stmt->execute([$patient_id]);
$active_meds = $stmt->fetchColumn();

// Start output buffering
ob_start();
?>
<div class="container-fluid">
    <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>

    <!-- Quick stats cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Upcoming Appointments</h5>
                    <p class="card-text display-6"><?php echo $upcoming_appointments; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Unpaid Bills</h5>
                    <p class="card-text display-6"><?php echo $unpaid_bills; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Active Medications</h5>
                    <p class="card-text display-6"><?php echo $active_meds; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Layout</h5>
                    <p class="card-text">
                        <a href="?set_layout=sidebar" class="btn btn-sm btn-light">Sidebar</a>
                        <a href="?set_layout=topnav" class="btn btn-sm btn-light">TopNav</a>
                        <a href="?set_layout=compact" class="btn btn-sm btn-light">Compact</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Appointments -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Blood Pressure Trends</div>
                <div class="card-body">
                    <canvas id="bpChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Upcoming Appointments</div>
                <div class="card-body" id="appointment-list">
                    <!-- Loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Load appointments via AJAX
fetch('/medtrack/api/appointments.php')
    .then(response => response.text())
    .then(html => {
        document.getElementById('appointment-list').innerHTML = html;
    });
</script>
<?php
$content_html = ob_get_clean();
$page_title = "Dashboard";
$current_page = 'dashboard';
include '../../includes/layout/layout_selector.php';
?>