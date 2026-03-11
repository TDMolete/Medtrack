<?php
require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isDoctor()) { header("Location: /medtrack/unauthorized.php"); exit; }

// Fetch stats (total patients, today's appointments, etc.)
$doctor_id = getDoctorIdByUserId($pdo, $_SESSION['user_id']);
// ... queries

$page_title = "Doctor Dashboard";
$current_page = 'doctor_dashboard';
$content_page = 'dashboard_content.php';
include '../../includes/layout/layout_selector.php';