<?php
/**
 * Doctor Dashboard
 * 
 * Shows summary statistics and upcoming appointments.
 */

require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isDoctor()) { header("Location: /medtrack/unauthorized.php"); exit; }

$doctor_name = $_SESSION['username']; // assuming doctor's username matches their display name

// Count today's appointments
$stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_name = ? AND appointment_date = CURDATE()");
$stmt->execute([$doctor_name]);
$today_appointments = $stmt->fetchColumn();

// Count total distinct patients
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT patient_id) FROM appointments WHERE doctor_name = ?");
$stmt->execute([$doctor_name]);
$total_patients = $stmt->fetchColumn();

// Upcoming appointments (next 5)
$stmt = $pdo->prepare("SELECT a.*, p.full_name FROM appointments a JOIN patients p ON a.patient_id = p.patient_id WHERE a.doctor_name = ? AND a.appointment_date >= CURDATE() ORDER BY a.appointment_date, a.appointment_time LIMIT 5");
$stmt->execute([$doctor_name]);
$upcoming = $stmt->fetchAll();

// Start output buffering
ob_start();
?>
<div class="container-fluid">
    <h2>Welcome, Dr. <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Today's Appointments</h5>
                    <p class="card-text display-6"><?php echo $today_appointments; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Patients</h5>
                    <p class="card-text display-6"><?php echo $total_patients; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
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

    <h4 class="mt-5">Upcoming Appointments</h4>
    <?php if ($upcoming): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($upcoming as $apt): ?>
                <tr>
                    <td><?php echo htmlspecialchars($apt['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($apt['appointment_time']); ?></td>
                    <td><?php echo htmlspecialchars($apt['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($apt['reason']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No upcoming appointments.</p>
    <?php endif; ?>
</div>
<?php
$content_html = ob_get_clean();
$page_title = "Doctor Dashboard";
$current_page = 'doctor_dashboard';
include '../../includes/layout/layout_selector.php';