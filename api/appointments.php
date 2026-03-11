<?php
/**
 * API Endpoint: Appointments
 * 
 * GET:  Returns HTML list of upcoming appointments for the logged-in patient.
 * POST: Books a new appointment (expects JSON or form data with CSRF token).
 */

require_once '../includes/config/db.php';
require_once '../includes/functions.php';

// Set JSON content type header (for error responses; GET will return HTML on success)
header('Content-Type: application/json');

// Authenticate user
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthenticated']);
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Only patients can view their appointments via this widget (doctors have their own page)
        if (!isPatient()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Forbidden']);
            exit;
        }

        $patient_id = getPatientIdByUserId($pdo, $user_id);
        if (!$patient_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Patient profile not found']);
            exit;
        }

        // Fetch upcoming scheduled appointments
        $stmt = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ? AND appointment_date >= CURDATE() AND status='scheduled' ORDER BY appointment_date, appointment_time LIMIT 5");
        $stmt->execute([$patient_id]);
        $appointments = $stmt->fetchAll();

        // Switch to HTML content type
        header('Content-Type: text/html');
        if (empty($appointments)) {
            echo '<p>No upcoming appointments. <a href="/medtrack/pages/patient/appointments.php">Book one now</a>.</p>';
        } else {
            echo '<table class="table table-sm">';
            echo '<thead><tr><th>Date</th><th>Time</th><th>Doctor</th><th>Reason</th></tr></thead><tbody>';
            foreach ($appointments as $apt) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($apt['appointment_date']) . '</td>';
                echo '<td>' . htmlspecialchars($apt['appointment_time']) . '</td>';
                echo '<td>' . htmlspecialchars($apt['doctor_name']) . '</td>';
                echo '<td>' . htmlspecialchars($apt['reason']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Only patients can book appointments
        if (!isPatient()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Forbidden']);
            exit;
        }

        // Verify CSRF token
        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!verifyCSRFToken($csrf_token)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
            exit;
        }

        $patient_id = getPatientIdByUserId($pdo, $user_id);
        if (!$patient_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Patient profile not found']);
            exit;
        }

        // Validate input
        $doctor = trim($_POST['doctor_name'] ?? '');
        $date = $_POST['appointment_date'] ?? '';
        $time = $_POST['appointment_time'] ?? '';
        $reason = trim($_POST['reason'] ?? '');

        if (empty($doctor) || empty($date) || empty($time)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Doctor, date and time are required']);
            exit;
        }

        // Optional: validate date format (YYYY-MM-DD) and time format (HH:MM)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !preg_match('/^\d{2}:\d{2}$/', $time)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid date or time format']);
            exit;
        }

        // Insert appointment
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_name, appointment_date, appointment_time, reason) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$patient_id, $doctor, $date, $time, $reason])) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Database error']);
        }
        exit;
    }

    // Method not allowed
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}