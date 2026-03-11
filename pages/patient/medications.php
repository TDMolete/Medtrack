<?php
require_once '../includes/config/db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: /medtrack/auth/login.php"); exit; }

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT patient_id FROM patients WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();
$patient_id = $patient['patient_id'];

// Add medication
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_med'])) {
    $name = $_POST['medication_name'];
    $dosage = $_POST['dosage'];
    $frequency = $_POST['frequency'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $notes = $_POST['notes'];
    $stmt = $pdo->prepare("INSERT INTO medications (patient_id, medication_name, dosage, frequency, start_date, end_date, notes) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$patient_id, $name, $dosage, $frequency, $start, $end, $notes]);
    header("Location: medications.php");
    exit;
}

// Fetch medications
$stmt = $pdo->prepare("SELECT * FROM medications WHERE patient_id = ? ORDER BY end_date DESC");
$stmt->execute([$patient_id]);
$meds = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="main-content p-4" style="margin-left: 250px;">
    <h2>Medication Schedule</h2>

    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#addMedForm">Add Medication</button>
    <div class="collapse mb-4" id="addMedForm">
        <div class="card card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="medication_name" class="form-label">Medication Name</label>
                    <input type="text" class="form-control" id="medication_name" name="medication_name" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="dosage" class="form-label">Dosage</label>
                        <input type="text" class="form-control" id="dosage" name="dosage" placeholder="e.g., 500mg">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="frequency" class="form-label">Frequency</label>
                        <input type="text" class="form-control" id="frequency" name="frequency" placeholder="e.g., twice daily">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                </div>
                <button type="submit" name="add_med" class="btn btn-success">Add</button>
            </form>
        </div>
    </div>

    <?php if ($meds): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Medication</th>
                    <th>Dosage</th>
                    <th>Frequency</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($meds as $med): ?>
                <tr>
                    <td><?php echo htmlspecialchars($med['medication_name']); ?></td>
                    <td><?php echo htmlspecialchars($med['dosage']); ?></td>
                    <td><?php echo htmlspecialchars($med['frequency']); ?></td>
                    <td><?php echo $med['start_date']; ?></td>
                    <td><?php echo $med['end_date']; ?></td>
                    <td><?php echo htmlspecialchars($med['notes']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No medications added yet.</p>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>