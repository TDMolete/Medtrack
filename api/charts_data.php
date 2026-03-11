<?php
/**
 * API Endpoint: Chart Data (Blood Pressure)
 * 
 * GET: Returns JSON with labels (dates) and systolic readings for the last 6 entries.
 */

require_once '../includes/config/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Only patients have BP readings (you could extend to doctors as well)
if (!isPatient()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Forbidden']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT reading_date, systolic FROM bp_readings WHERE user_id = ? ORDER BY reading_date ASC");
    $stmt->execute([$user_id]);
    $rows = $stmt->fetchAll();

    $data = ['labels' => [], 'systolic' => []];
    foreach ($rows as $row) {
        $data['labels'][] = $row['reading_date'];
        $data['systolic'][] = (int)$row['systolic'];
    }

    echo json_encode(['success' => true, 'data' => $data]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}