<?php
require_once '../includes/config/db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: /medtrack/auth/login.php"); exit; }

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT p.*, u.email, u.username FROM patients p JOIN users u ON p.user_id = u.user_id WHERE p.user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $emergency = $_POST['emergency_contact'];

    $stmt = $pdo->prepare("UPDATE patients SET full_name=?, date_of_birth=?, gender=?, phone=?, address=?, emergency_contact=? WHERE user_id=?");
    $stmt->execute([$full_name, $dob, $gender, $phone, $address, $emergency, $user_id]);
    $success = "Profile updated.";
    // refresh
    $stmt->execute([$user_id]);
    $patient = $stmt->fetch();
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="main-content p-4" style="margin-left: 250px;">
    <h2>My Profile</h2>
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <form method="post" class="card p-4">
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
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
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>