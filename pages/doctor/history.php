<?php
/**
 * Doctor View Patient History
 * 
 * Displays a patient's medical history and appointment history.
 * Access is restricted to patients who have had appointments with this doctor.
 */

require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isDoctor()) { header("Location: /medtrack/unauthorized.php"); exit; }

$doctor_name = $_SESSION['username'];
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

if (!$patient_id) {
    $_SESSION['flash_error'] = "No patient selected.";
    header("Location: patient_list.php");
    exit;
}

// Verify that this doctor has seen this patient (has at least one appointment)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_name = ? AND patient_id = ?");
$stmt->execute([$doctor_name, $patient_id]);
if ($stmt->fetchColumn() == 0) {
    http_response_code(403);
    die("You are not authorized to view this patient's records.");
}

// Get patient info
$stmt = $pdo->prepare("SELECT full_name, date_of_birth, phone, gender FROM patients WHERE patient_id = ?");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch();
if (!$patient) {
    $_SESSION['flash_error'] = "Patient not found.";
    header("Location: patient_list.php");
    exit;
}

// Get medical history
$stmt = $pdo->prepare("SELECT * FROM medical_history WHERE patient_id = ? ORDER BY diagnosis_date DESC");
$stmt->execute([$patient_id]);
$history = $stmt->fetchAll();

// Get appointment history with this doctor
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ? AND doctor_name = ? ORDER BY appointment_date DESC");
$stmt->execute([$patient_id, $doctor_name]);
$appointments = $stmt->fetchAll();

ob_start();
?>
<div class="container-fluid">
    <h2>Patient History: <?php echo htmlspecialchars($patient['full_name']); ?></h2>
    
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <i class="fas fa-info-circle"></i> Patient Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>DOB:</strong> <?php echo $patient['date_of_birth'] ?: '—'; ?></div>
                <div class="col-md-3"><strong>Gender:</strong> <?php echo $patient['gender'] ?: '—'; ?></div>
                <div class="col-md-3"><strong>Phone:</strong> <?php echo htmlspecialchars($patient['phone'] ?: '—'); ?></div>
            </div>
        </div>
    </div>

    <h4>Medical History</h4>
    <?php if ($history): ?>
        <table class="table table-striped" id="medical-history-table">
            <thead class="table-dark">
                <tr>
                    <th>Illness</th>
                    <th>Diagnosis Date</th>
                    <th>Doctor's Notes</th>
                    <th>Treatment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $rec): ?>
                <tr>
                    <td><?php echo htmlspecialchars($rec['illness_name']); ?></td>
                    <td><?php echo $rec['diagnosis_date']; ?></td>
                    <td><?php echo htmlspecialchars($rec['doctor_notes']); ?></td>
                    <td><?php echo htmlspecialchars($rec['treatment']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No medical history records.</p>
    <?php endif; ?>

    <h4 class="mt-5">Appointment History (with you)</h4>
    <?php if ($appointments): ?>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $apt): ?>
                <tr>
                    <td><?php echo $apt['appointment_date']; ?></td>
                    <td><?php echo $apt['appointment_time']; ?></td>
                    <td><?php echo htmlspecialchars($apt['reason']); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $apt['status'] == 'scheduled' ? 'primary' : ($apt['status'] == 'completed' ? 'success' : 'secondary'); ?>">
                            <?php echo $apt['status']; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No appointments with this patient.</p>
    <?php endif; ?>

    <a href="patient_list.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Back to Patient List</a>
</div>
<?php
$content_html = ob_get_clean();
$page_title = "Patient History";
$current_page = 'doctor_history';
include '../../includes/layout/layout_selector.php';