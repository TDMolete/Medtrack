<?php
require_once '../includes/config/db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: /medtrack/auth/login.php"); exit; }

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT patient_id FROM patients WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();
$patient_id = $patient['patient_id'];

// Handle add new record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $illness = $_POST['illness_name'];
    $diagnosis = $_POST['diagnosis_date'];
    $notes = $_POST['doctor_notes'];
    $treatment = $_POST['treatment'];
    $stmt = $pdo->prepare("INSERT INTO medical_history (patient_id, illness_name, diagnosis_date, doctor_notes, treatment) VALUES (?,?,?,?,?)");
    $stmt->execute([$patient_id, $illness, $diagnosis, $notes, $treatment]);
    header("Location: medical_history.php");
    exit;
}

// Fetch all records
$stmt = $pdo->prepare("SELECT * FROM medical_history WHERE patient_id = ? ORDER BY diagnosis_date DESC");
$stmt->execute([$patient_id]);
$history = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="main-content p-4" style="margin-left: 250px;">
    <h2>Medical History</h2>

    <!-- Add new record form (simple) -->
    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#addForm">Add New Record</button>
    <div class="collapse mb-4" id="addForm">
        <div class="card card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="illness_name" class="form-label">Illness / Condition</label>
                    <input type="text" class="form-control" id="illness_name" name="illness_name" required>
                </div>
                <div class="mb-3">
                    <label for="diagnosis_date" class="form-label">Diagnosis Date</label>
                    <input type="date" class="form-control" id="diagnosis_date" name="diagnosis_date">
                </div>
                <div class="mb-3">
                    <label for="doctor_notes" class="form-label">Doctor's Notes</label>
                    <textarea class="form-control" id="doctor_notes" name="doctor_notes" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label for="treatment" class="form-label">Treatment</label>
                    <textarea class="form-control" id="treatment" name="treatment" rows="2"></textarea>
                </div>
                <button type="submit" name="add" class="btn btn-success">Save</button>
            </form>
        </div>
    </div>

    <!-- Display history -->
    <?php if ($history): ?>
        <table class="table table-bordered">
            <thead>
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
        <p>No medical history records found. Add one using the button above.</p>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>