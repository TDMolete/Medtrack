<?php
require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isPatient()) { header("Location: /medtrack/unauthorized.php"); exit; }

$user_id = $_SESSION['user_id'];
$patient_id = getPatientIdByUserId($pdo, $user_id);

// Handle add new record
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_record'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $illness = trim($_POST['illness_name']);
        $diagnosis = $_POST['diagnosis_date'] ?: null;
        $notes = trim($_POST['doctor_notes']);
        $treatment = trim($_POST['treatment']);
        $stmt = $pdo->prepare("INSERT INTO medical_history (patient_id, illness_name, diagnosis_date, doctor_notes, treatment) VALUES (?,?,?,?,?)");
        if ($stmt->execute([$patient_id, $illness, $diagnosis, $notes, $treatment])) {
            $success = "Record added.";
        } else {
            $error = "Failed to add record.";
        }
    }
}

// Fetch all records
$stmt = $pdo->prepare("SELECT * FROM medical_history WHERE patient_id = ? ORDER BY diagnosis_date DESC");
$stmt->execute([$patient_id]);
$history = $stmt->fetchAll();

ob_start();
?>
<div class="container-fluid">
    <h2>Medical History</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Add new record button -->
    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#addForm">
        <i class="fas fa-plus"></i> Add New Record
    </button>

    <!-- Export buttons -->
    <button class="btn btn-outline-success mb-3 ms-2" onclick="exportTableToPDF('history-table', 'medical_history')">
        <i class="fas fa-file-pdf"></i> Export PDF
    </button>
    <button class="btn btn-outline-info mb-3 ms-2" onclick="exportTableToCSV('history-table', 'medical_history')">
        <i class="fas fa-file-csv"></i> Export CSV
    </button>

    <!-- Add form (collapsible) -->
    <div class="collapse mb-4" id="addForm">
        <div class="card card-body">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <div class="mb-3">
                    <label for="illness_name" class="form-label">Illness / Condition *</label>
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
                <button type="submit" name="add_record" class="btn btn-success">Save Record</button>
            </form>
        </div>
    </div>

    <!-- History table -->
    <?php if ($history): ?>
        <table class="table table-bordered table-striped" id="history-table">
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
        <p>No medical history records found.</p>
    <?php endif; ?>
</div>
<?php
$content_html = ob_get_clean();
$page_title = "Medical History";
$current_page = 'medical_history';
include '../../includes/layout/layout_selector.php';
?>