<?php
/**
 * Core Helper Functions
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Authentication & Role Helpers
 */
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

function redirectBasedOnRole() {
    $role = $_SESSION['role'] ?? 'patient';
    switch ($role) {
        case 'doctor':
            header("Location: /medtrack/pages/doctor/dashboard.php");
            break;
        case 'admin':
            header("Location: /medtrack/pages/admin/dashboard.php");
            break;
        case 'patient':
        default:
            header("Location: /medtrack/pages/patient/dashboard.php");
            break;
    }
    exit;
}

/**
 * CSRF Protection
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Database ID Helpers
 */
function getPatientIdByUserId($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT patient_id FROM patients WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();
    return $row ? $row['patient_id'] : null;
}

function getDoctorIdByUserId($pdo, $user_id) {
    // Assuming doctors table exists with user_id foreign key
    $stmt = $pdo->prepare("SELECT doctor_id FROM doctors WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();
    return $row ? $row['doctor_id'] : null;
}

/**
 * Notification Helpers (optional)
 */
function getUnreadNotificationCount($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn();
}

/**
 * Layout Helper – sets the current layout in session
 */
function setLayout($layout) {
    $allowed = ['sidebar', 'topnav', 'compact'];
    if (in_array($layout, $allowed)) {
        $_SESSION['layout'] = $layout;
    }
}

/**
 * Debugging (only enable in development)
 */
function debug_log($message) {
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        error_log("[MEDTRACK DEBUG] " . print_r($message, true));
    }
}
?>