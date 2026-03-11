<?php
require_once '../includes/config/db.php';
require_once '../includes/functions.php';
redirectIfNotLoggedIn();
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT reading_date, systolic FROM bp_readings WHERE user_id = ? ORDER BY reading_date ASC");
$stmt->execute([$user_id]);
$data = ['labels' => [], 'systolic' => []];
while ($row = $stmt->fetch()) {
    $data['labels'][] = $row['reading_date'];
    $data['systolic'][] = $row['systolic'];
}
header('Content-Type: application/json');
echo json_encode($data);