<?php
// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isPatient() {
    return ($_SESSION['role'] ?? '') === 'patient';
}

function isDoctor() {
    return ($_SESSION['role'] ?? '') === 'doctor';
}

function isAdmin() {
    return ($_SESSION['role'] ?? '') === 'admin';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: /medtrack/auth/login.php");
        exit;
    }
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function getPatientIdByUserId($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT patient_id FROM patients WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();
    return $row ? $row['patient_id'] : null;
}

function getDoctorIdByUserId($pdo, $user_id) {
    // You may have a doctors table – adjust accordingly
    $stmt = $pdo->prepare("SELECT doctor_id FROM doctors WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();
    return $row ? $row['doctor_id'] : null;
}
?>