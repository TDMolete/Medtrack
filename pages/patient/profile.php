<?php
require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isPatient()) { header("Location: /medtrack/unauthorized.php"); exit; }

$user_id = $_SESSION['user_id'];
$patient_id = getPatientIdByUserId($pdo, $user_id);

// Fetch current data
$stmt = $pdo->prepare("SELECT p.*, u.email, u.username FROM patients p JOIN users u ON p.user_id = u.user_id WHERE p.user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();

if (!$patient) {
    die("Patient profile not found.");
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $message = '<div class="alert alert-danger">Invalid security token.</div>';
    } else {
        $full_name = trim($_POST['full_name']);
        $dob = $_POST['date_of_birth'] ?: null;
        $gender = $_POST['gender'];
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $emergency = trim($_POST['emergency_contact']);

        $stmt = $pdo->prepare("UPDATE patients SET full_name=?, date_of_birth=?, gender=?, phone=?, address=?, emergency_contact=? WHERE user_id=?");
        if ($stmt->execute([$full_name, $dob, $gender, $phone, $address, $emergency, $user_id])) {
            $message = '<div class="alert alert-success">Profile updated successfully.</div>';
            // Refresh data
            $stmt = $pdo->prepare("SELECT p.*, u.email, u.username FROM patients p JOIN users u ON p.user_id = u.user_id WHERE p.user_id = ?");
            $stmt->execute([$user_id]);
            $patient = $stmt->fetch();
        } else {
            $message = '<div class="alert alert-danger">Update failed.</div>';
        }
    }
}

ob_start();
?>
<div class="container-fluid">
    <h2>My Profile</h2>
    <?php echo $message; ?>
    <div class="card">
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($patient['username']); ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($patient['email']); ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($patient['full_name']); ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo $patient['date_of_birth']; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="Male" <?php if($patient['gender']=='Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if($patient['gender']=='Female') echo 'selected'; ?>>Female</option>
                            <option value="Other" <?php if($patient['gender']=='Other') echo 'selected'; ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address"><?php echo htmlspecialchars($patient['address']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="emergency_contact" class="form-label">Emergency Contact</label>
                    <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" value="<?php echo htmlspecialchars($patient['emergency_contact']); ?>">
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>
<?php
$content_html = ob_get_clean();
$page_title = "My Profile";
$current_page = 'profile';
include '../../includes/layout/layout_selector.php';
?>