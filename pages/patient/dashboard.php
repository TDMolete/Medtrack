<?php
require_once '../includes/config/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /medtrack/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
// Get patient id
$stmt = $pdo->prepare("SELECT patient_id, full_name FROM patients WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();
$patient_id = $patient['patient_id'];

// Upcoming appointments
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ? AND appointment_date >= CURDATE() AND status='scheduled' ORDER BY appointment_date, appointment_time LIMIT 5");
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll();

// Unpaid bills
$stmt = $pdo->prepare("SELECT COUNT(*) FROM bills WHERE patient_id = ? AND status = 'unpaid'");
$stmt->execute([$patient_id]);
$unpaid_count = $stmt->fetchColumn();

// Medications due soon (simplified: any active)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM medications WHERE patient_id = ? AND end_date >= CURDATE()");
$stmt->execute([$patient_id]);
$med_count = $stmt->fetchColumn();

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="main-content p-4" style="margin-left: 250px;">
    <h2>Welcome, <?php echo htmlspecialchars($patient['full_name']); ?> 👋</h2>

    <!-- Quick stats -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Upcoming Appointments</h5>
                    <p class="card-text display-6"><?php echo count($appointments); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Unpaid Bills</h5>
                    <p class="card-text display-6"><?php echo $unpaid_count; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Active Medications</h5>
                    <p class="card-text display-6"><?php echo $med_count; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming appointments list -->
    <div class="mt-4">
        <h4>Your Upcoming Appointments</h4>
        <?php if ($appointments): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Doctor</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $apt): ?>
                    <tr>
                        <td><?php echo $apt['appointment_date']; ?></td>
                        <td><?php echo $apt['appointment_time']; ?></td>
                        <td><?php echo htmlspecialchars($apt['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($apt['reason']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No upcoming appointments. <a href="appointments.php">Book one now</a>.</p>
        <?php endif; ?>
    </div>

    <!-- Notifications area (placeholder) -->
    <div class="alert alert-info mt-4">
        <strong>Notification:</strong> Remember to take your medication as scheduled. Visit the <a href="medications.php">Medications</a> page for details.
    </div>
</div>

<?php include '../includes/footer.php'; ?>