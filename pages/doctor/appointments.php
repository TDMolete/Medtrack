<?php
/**
 * Doctor Appointments View
 * 
 * Shows all appointments (filterable: upcoming / all).
 */

require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isDoctor()) { header("Location: /medtrack/unauthorized.php"); exit; }

$doctor_name = $_SESSION['username'];

// Filter: 'upcoming' (default) or 'all'
$filter = $_GET['filter'] ?? 'upcoming';
if (!in_array($filter, ['upcoming', 'all'])) {
    $filter = 'upcoming';
}

if ($filter === 'all') {
    $stmt = $pdo->prepare("
        SELECT a.*, p.full_name 
        FROM appointments a 
        JOIN patients p ON a.patient_id = p.patient_id 
        WHERE a.doctor_name = ? 
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT a.*, p.full_name 
        FROM appointments a 
        JOIN patients p ON a.patient_id = p.patient_id 
        WHERE a.doctor_name = ? AND a.appointment_date >= CURDATE() 
        ORDER BY a.appointment_date, a.appointment_time
    ");
}
$stmt->execute([$doctor_name]);
$appointments = $stmt->fetchAll();

ob_start();
?>
<div class="container-fluid">
    <h2>Appointments</h2>

    <div class="mb-3">
        <a href="?filter=upcoming" class="btn btn-sm <?php echo ($filter=='upcoming')?'btn-primary':'btn-outline-primary'; ?>">Upcoming</a>
        <a href="?filter=all" class="btn btn-sm <?php echo ($filter=='all')?'btn-primary':'btn-outline-primary'; ?>">All</a>
    </div>

    <?php if ($appointments): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $apt): ?>
                <tr>
                    <td><?php echo htmlspecialchars($apt['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($apt['appointment_time']); ?></td>
                    <td><?php echo htmlspecialchars($apt['full_name']); ?></td>
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
        <div class="alert alert-info">No appointments found.</div>
    <?php endif; ?>
</div>
<?php
$content_html = ob_get_clean();
$page_title = "Appointments";
$current_page = 'doctor_appointments';
include '../../includes/layout/layout_selector.php';