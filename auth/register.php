<?php
/**
 * Registration Page
 * 
 * Features:
 * - CSRF protection
 * - Strong input validation (email format, password strength)
 * - Transactional insert (users + patients)
 * - Flash messages
 * - PRG pattern
 */

require_once '../includes/config/db.php';
require_once '../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect
if (isset($_SESSION['user_id'])) {
    redirectBasedOnRole();
    exit;
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid security token. Please refresh the page.";
    } else {
        // Get and sanitize inputs
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $full_name = trim($_POST['full_name'] ?? '');

        // Validation
        if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters.";
        } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $error = "Password must contain at least one letter and one number.";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $error = "Username must be between 3 and 50 characters.";
        } else {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = "Username or email already taken.";
            } else {
                // Begin transaction
                $pdo->beginTransaction();
                try {
                    // Hash password
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    // Insert into users
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'patient')");
                    $stmt->execute([$username, $email, $password_hash]);
                    $user_id = $pdo->lastInsertId();

                    // Insert into patients
                    $stmt = $pdo->prepare("INSERT INTO patients (user_id, full_name) VALUES (?, ?)");
                    $stmt->execute([$user_id, $full_name]);

                    $pdo->commit();

                    // Success message
                    $_SESSION['flash_success'] = "Registration successful! You can now log in.";
                    header("Location: login.php");
                    exit;

                } catch (Exception $e) {
                    $pdo->rollBack();
                    error_log("Registration error: " . $e->getMessage());
                    $error = "Registration failed. Please try again later.";
                }
            }
        }
    }

    // If there's an error, store in session flash and redirect back
    if ($error) {
        $_SESSION['flash_error'] = $error;
        header("Location: register.php");
        exit;
    }
}

// Check for flash messages
if (isset($_SESSION['flash_error'])) {
    $error = $_SESSION['flash_error'];
    unset($_SESSION['flash_error']);
}
if (isset($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MedTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .register-container { max-width: 500px; margin: 40px auto; }
    </style>
</head>
<body>
    <div class="container register-container">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4>Create MedTrack Account</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <form method="post" action="register.php">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div class="form-text">3-50 characters</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">Minimum 8 characters, at least one letter and one number.</div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <hr>
                <p class="text-center mb-0">Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
<?php
ob_end_flush();
?>