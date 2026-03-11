<?php
/**
 * Doctor Patient List
 * 
 * Lists all patients who have had appointments with this doctor.
 * Clicking on a patient shows their full history.
 */

require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isDoctor()) { header("Location: /medtrack/unauthorized.php"); exit; }

$doctor_name = $_SESSION['username'];

// Fetch distinct patients with their latest appointment date
$stmt = $pdo->prepare("
    SELECT DISTINCT p.patient_id, p.full_name, p.date_of_birth, p.phone, p.gender,
           (SELECT MAX(appointment_date) FROM appointments WHERE patient_id = p.patient_id AND doctor_name = ?) AS last_visit
    FROM patients p
    JOIN appointments a ON p.patient_id = a.patient_id
    WHERE a.doctor_name = ?
    ORDER BY last_visit DESC
");
$stmt->execute([$doctor_name, $doctor_name]);
$patients = $stmt->fetchAll();

ob_start();
?>
<div class="container-fluid">
    <h2>My Patients</h2>

    <?php if ($patients): ?>
        <table class="table table-striped" id="patients-table">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Last Visit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patients as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['full_name']); ?></td>
                    <td><?php echo $p['date_of_birth'] ?: '—'; ?></td>
                    <td><?php echo $p['gender'] ?: '—'; ?></td>
                    <td><?php echo htmlspecialchars($p['phone'] ?: '—'); ?></td>
                    <td><?php echo $p['last_visit'] ?: '—'; ?></td>
                    <td>
                        <a href="history.php?patient_id=<?php echo $p['patient_id']; ?>" class="btn btn-sm btn-info">
                            <i class="fas fa-file-medical"></i> View History
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">You have no patients yet.</div>
    <?php endif; ?>
</div>
<?php
$content_html = ob_get_clean();
$page_title = "My Patients";
$current_page = 'patient_list';
include '../../includes/layout/layout_selector.php';