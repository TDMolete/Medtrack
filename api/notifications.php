<?php
require_once '../includes/config/db.php';
require_once '../includes/functions.php';
redirectIfNotLoggedIn();
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
$stmt->execute([$user_id]);
$count = $stmt->fetchColumn();
echo json_encode(['count' => $count]);