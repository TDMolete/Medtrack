<?php
/**
 * Login Page
 * 
 * Features:
 * - CSRF protection
 * - Rate limiting (5 attempts → 15 min lockout)
 * - Session flash messages for errors
 * - PRG pattern
 */

require_once '../includes/config/db.php';
require_once '../includes/functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    redirectBasedOnRole();
    exit;
}

// Initialize login attempts counter
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['login_lockout_time'] = 0;
}

// Check if locked out
$lockout_time = 15 * 60; // 15 minutes in seconds
if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['login_lockout_time']) < $lockout_time) {
    $remaining = $lockout_time - (time() - $_SESSION['login_lockout_time']);
    $minutes = ceil($remaining / 60);
    $error = "Too many failed attempts. Please try again in $minutes minutes.";
    $show_form = false;
} else {
    $show_form = true;
    // Reset attempts if lockout period passed
    if ($_SESSION['login_attempts'] >= 5) {
        $_SESSION['login_attempts'] = 0;
    }
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $show_form) {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid security token. Please refresh the page.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Basic validation
        if (empty($username) || empty($password)) {
            $error = "Both username and password are required.";
        } else {
            // Fetch user from database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // Successful login: reset attempts, regenerate session ID, set session vars
                $_SESSION['login_attempts'] = 0;
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                redirectBasedOnRole();
                exit;
            } else {
                // Failed attempt
                $_SESSION['login_attempts']++;
                if ($_SESSION['login_attempts'] >= 5) {
                    $_SESSION['login_lockout_time'] = time();
                }
                $error = "Invalid username or password.";
            }
        }
    }

    // If there's an error, store in session flash and redirect to avoid resubmission
    if ($error) {
        $_SESSION['flash_error'] = $error;
        header("Location: login.php");
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

// Start output buffering
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MedTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .login-container { max-width: 400px; margin: 80px auto; }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4>MedTrack Login</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if ($show_form): ?>
                <form method="post" action="login.php">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <?php else: ?>
                    <p class="text-center">Too many failed attempts. Please try again later.</p>
                <?php endif; ?>
                <hr>
                <p class="text-center mb-0">Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
<?php
ob_end_flush();
?>