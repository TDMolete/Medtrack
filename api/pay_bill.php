<?php
/**
 * API Endpoint: Pay Bill
 * 
 * POST: Marks a specific bill as paid (updates status and sets paid_date to today).
 */

require_once '../includes/config/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Only patients can pay bills
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

$user_id = $_SESSION['user_id'];
$bill_id = isset($_POST['bill_id']) ? intval($_POST['bill_id']) : 0;

if ($bill_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid bill ID']);
    exit;
}

try {
    // Get patient_id
    $patient_id = getPatientIdByUserId($pdo, $user_id);
    if (!$patient_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Patient profile not found']);
        exit;
    }

    // Verify bill belongs to patient and is unpaid
    $stmt = $pdo->prepare("SELECT bill_id FROM bills WHERE bill_id = ? AND patient_id = ? AND status = 'unpaid'");
    $stmt->execute([$bill_id, $patient_id]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Bill not found or already paid']);
        exit;
    }

    // Update bill
    $stmt = $pdo->prepare("UPDATE bills SET status = 'paid', paid_date = CURDATE() WHERE bill_id = ?");
    if ($stmt->execute([$bill_id])) {
        echo json_encode(['success' => true, 'paid_date' => date('Y-m-d')]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}