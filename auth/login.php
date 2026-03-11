<?php
require_once '../includes/config/db.php';
require_once '../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    redirectBasedOnRole();
    exit;
}

// Rate limiting
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['login_lockout_time'] = 0;
}

$lockout_time = 15 * 60; // 15 minutes
$show_form = true;
$error = '';

if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['login_lockout_time']) < $lockout_time) {
    $remaining = $lockout_time - (time() - $_SESSION['login_lockout_time']);
    $minutes = ceil($remaining / 60);
    $error = "Too many failed attempts. Please try again in $minutes minutes.";
    $show_form = false;
} else {
    if ($_SESSION['login_attempts'] >= 5) {
        $_SESSION['login_attempts'] = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $show_form) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid security token. Please refresh the page.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = "Both username and password are required.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['login_attempts'] = 0;
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                redirectBasedOnRole();
                exit;
            } else {
                $_SESSION['login_attempts']++;
                if ($_SESSION['login_attempts'] >= 5) {
                    $_SESSION['login_lockout_time'] = time();
                }
                $error = "Invalid username or password.";
            }
        }
    }
}

$page_title = "Login - MedTrack";
include '../includes/header.php';
?>

<div class="container mt-5" style="max-width: 400px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>MedTrack Login</h4>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
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

<?php include '../includes/footer.php'; ?>