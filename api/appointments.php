<?php
require_once '../includes/config/db.php';
require_once '../includes/functions.php';
redirectIfNotLoggedIn();
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Invalid CSRF']);
        exit;
    }
    // Get patient_id
    $patient_id = getPatientIdByUserId($pdo, $user_id);
    if (!$patient_id) {
        echo json_encode(['success' => false, 'error' => 'Patient not found']);
        exit;
    }
    $doctor = $_POST['doctor_name'] ?? '';
    $date = $_POST['appointment_date'] ?? '';
    $time = $_POST['appointment_time'] ?? '';
    $reason = $_POST['reason'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_name, appointment_date, appointment_time, reason) VALUES (?,?,?,?,?)");
    if ($stmt->execute([$patient_id, $doctor, $date, $time, $reason])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Return HTML list of appointments for the current patient/doctor
    if ($role === 'patient') {
        $patient_id = getPatientIdByUserId($pdo, $user_id);
        $stmt = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ? ORDER BY appointment_date, appointment_time");
        $stmt->execute([$patient_id]);
    } elseif ($role === 'doctor') {
        $doctor_id = getDoctorIdByUserId($pdo, $user_id); // need mapping
        // For simplicity, assume doctor_name matches session username
        $doctor_name = $_SESSION['username'];
        $stmt = $pdo->prepare("SELECT * FROM appointments WHERE doctor_name = ? ORDER BY appointment_date, appointment_time");
        $stmt->execute([$doctor_name]);
    }
    $appointments = $stmt->fetchAll();
    // Render simple HTML table
    ?>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Doctor/Patient</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($appointments as $apt): ?>
            <tr>
                <td><?php echo $apt['appointment_date']; ?></td>
                <td><?php echo $apt['appointment_time']; ?></td>
                <td><?php echo $role === 'patient' ? $apt['doctor_name'] : 'Patient #'.$apt['patient_id']; ?></td>
                <td><?php echo htmlspecialchars($apt['reason']); ?></td>
                <td><?php echo $apt['status']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    exit;
}
?>