<?php
require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isPatient()) { header("Location: /medtrack/unauthorized.php"); exit; }

$user_id = $_SESSION['user_id'];
$patient_id = getPatientIdByUserId($pdo, $user_id);

// Add medication
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_med'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $name = trim($_POST['medication_name']);
        $dosage = trim($_POST['dosage']);
        $frequency = trim($_POST['frequency']);
        $start = $_POST['start_date'] ?: null;
        $end = $_POST['end_date'] ?: null;
        $notes = trim($_POST['notes']);
        $stmt = $pdo->prepare("INSERT INTO medications (patient_id, medication_name, dosage, frequency, start_date, end_date, notes) VALUES (?,?,?,?,?,?,?)");
        if ($stmt->execute([$patient_id, $name, $dosage, $frequency, $start, $end, $notes])) {
            $success = "Medication added.";
        } else {
            $error = "Failed to add medication.";
        }
    }
}

// Fetch medications
$stmt = $pdo->prepare("SELECT * FROM medications WHERE patient_id = ? ORDER BY end_date DESC");
$stmt->execute([$patient_id]);
$meds = $stmt->fetchAll();

ob_start();
?>
<div class="container-fluid">
    <h2>Medication Schedule</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#addMedForm">
        <i class="fas fa-plus"></i> Add Medication
    </button>

    <div class="collapse mb-4" id="addMedForm">
        <div class="card card-body">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <div class="mb-3">
                    <label for="medication_name" class="form-label">Medication Name *</label>
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
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                </div>
                <button type="submit" name="add_med" class="btn btn-success">Add</button>
            </form>
        </div>
    </div>

    <?php if ($meds): ?>
        <table class="table table-striped" id="meds-table">
            <thead class="table-dark">
                <tr>
                    <th>Medication</th>
                    <th>Dosage</th>
                    <th>Frequency</th>
                    <th>Start Date</th>
                    <th>End Date</th>
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
<?php
$content_html = ob_get_clean();
$page_title = "Medications";
$current_page = 'medications';
include '../../includes/layout/layout_selector.php';
?>