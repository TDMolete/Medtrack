<?php
require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isPatient()) { header("Location: /medtrack/unauthorized.php"); exit; }

$user_id = $_SESSION['user_id'];
$patient_id = getPatientIdByUserId($pdo, $user_id);

// Fetch stats
$stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE patient_id = ? AND appointment_date >= CURDATE() AND status='scheduled'");
$stmt->execute([$patient_id]);
$upcoming_appointments = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM bills WHERE patient_id = ? AND status='unpaid'");
$stmt->execute([$patient_id]);
$unpaid_bills = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM medications WHERE patient_id = ? AND end_date >= CURDATE()");
$stmt->execute([$patient_id]);
$active_meds = $stmt->fetchColumn();

$page_title = "Patient Dashboard";
$current_page = 'dashboard';
$content_page = 'dashboard_content.php'; // this file holds the actual HTML

include '../../includes/layout/layout_selector.php';