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

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid security token. Please refresh the page.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $full_name = trim($_POST['full_name'] ?? '');

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
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = "Username or email already taken.";
            } else {
                $pdo->beginTransaction();
                try {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'patient')");
                    $stmt->execute([$username, $email, $password_hash]);
                    $user_id = $pdo->lastInsertId();

                    $stmt = $pdo->prepare("INSERT INTO patients (user_id, full_name) VALUES (?, ?)");
                    $stmt->execute([$user_id, $full_name]);

                    $pdo->commit();
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
}

$page_title = "Register - MedTrack";
include '../includes/header.php';
?>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>Create MedTrack Account</h4>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
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

<?php include '../includes/footer.php'; ?>