<?php
require_once '../includes/config/db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: /medtrack/auth/login.php"); exit; }

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT patient_id FROM patients WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();
$patient_id = $patient['patient_id'];

// Book appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $doctor = $_POST['doctor_name'];
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];
    $reason = $_POST['reason'];
    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_name, appointment_date, appointment_time, reason) VALUES (?,?,?,?,?)");
    $stmt->execute([$patient_id, $doctor, $date, $time, $reason]);
    header("Location: appointments.php");
    exit;
}

// Cancel appointment
if (isset($_GET['cancel'])) {
    $apt_id = $_GET['cancel'];
    $stmt = $pdo->prepare("UPDATE appointments SET status='cancelled' WHERE appointment_id=? AND patient_id=?");
    $stmt->execute([$apt_id, $patient_id]);
    header("Location: appointments.php");
    exit;
}

// Fetch appointments
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ? ORDER BY appointment_date DESC, appointment_time DESC");
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="main-content p-4" style="margin-left: 250px;">
    <h2>Appointments</h2>

    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#bookForm">Book New Appointment</button>
    <div class="collapse mb-4" id="bookForm">
        <div class="card card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="doctor_name" class="form-label">Doctor's Name</label>
                    <input type="text" class="form-control" id="doctor_name" name="doctor_name">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="appointment_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="appointment_time" class="form-label">Time</label>
                        <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="reason" class="form-label">Reason for Visit</label>
                    <textarea class="form-control" id="reason" name="reason" rows="2"></textarea>
                </div>
                <button type="submit" name="book" class="btn btn-success">Book</button>
            </form>
        </div>
    </div>

    <h4>All Appointments</h4>
    <?php if ($appointments): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Doctor</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $apt): ?>
                <tr>
                    <td><?php echo $apt['appointment_date']; ?></td>
                    <td><?php echo $apt['appointment_time']; ?></td>
                    <td><?php echo htmlspecialchars($apt['doctor_name']); ?></td>
                    <td><?php echo htmlspecialchars($apt['reason']); ?></td>
                    <td><?php echo $apt['status']; ?></td>
                    <td>
                        <?php if ($apt['status'] == 'scheduled'): ?>
                            <a href="?cancel=<?php echo $apt['appointment_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?')">Cancel</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No appointments found.</p>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>