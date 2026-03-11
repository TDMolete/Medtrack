<?php
require_once '../includes/config/db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: /medtrack/auth/login.php"); exit; }

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT patient_id FROM patients WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();
$patient_id = $patient['patient_id'];

// Simulate adding a bill (for demo, we can add a form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_bill'])) {
    $amount = $_POST['amount'];
    $issue_date = $_POST['issue_date'];
    $stmt = $pdo->prepare("INSERT INTO bills (patient_id, amount, issue_date) VALUES (?, ?, ?)");
    $stmt->execute([$patient_id, $amount, $issue_date]);
    header("Location: billing.php");
    exit;
}

// Mark as paid
if (isset($_GET['pay'])) {
    $bill_id = $_GET['pay'];
    $stmt = $pdo->prepare("UPDATE bills SET status='paid', paid_date=CURDATE() WHERE bill_id=? AND patient_id=?");
    $stmt->execute([$bill_id, $patient_id]);
    header("Location: billing.php");
    exit;
}

// Fetch bills
$stmt = $pdo->prepare("SELECT * FROM bills WHERE patient_id = ? ORDER BY issue_date DESC");
$stmt->execute([$patient_id]);
$bills = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="main-content p-4" style="margin-left: 250px;">
    <h2>Billing</h2>

    <!-- Admin-style add bill (for demo) -->
    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#addBillForm">Add Sample Bill</button>
    <div class="collapse mb-4" id="addBillForm">
        <div class="card card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount (before VAT)</label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                </div>
                <div class="mb-3">
                    <label for="issue_date" class="form-label">Issue Date</label>
                    <input type="date" class="form-control" id="issue_date" name="issue_date" required>
                </div>
                <button type="submit" name="add_bill" class="btn btn-success">Add Bill</button>
            </form>
        </div>
    </div>

    <?php if ($bills): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Bill #</th>
                    <th>Issue Date</th>
                    <th>Amount (excl. VAT)</th>
                    <th>VAT (7.5%)</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Paid Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bills as $bill): ?>
                <tr>
                    <td><?php echo $bill['bill_id']; ?></td>
                    <td><?php echo $bill['issue_date']; ?></td>
                    <td>R<?php echo number_format($bill['amount'], 2); ?></td>
                    <td>R<?php echo number_format($bill['vat_amount'], 2); ?></td>
                    <td><strong>R<?php echo number_format($bill['total_amount'], 2); ?></strong></td>
                    <td><?php echo $bill['status']; ?></td>
                    <td><?php echo $bill['paid_date'] ?? '-'; ?></td>
                    <td>
                        <?php if ($bill['status'] == 'unpaid'): ?>
                            <a href="?pay=<?php echo $bill['bill_id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Mark as paid?')">Pay Now</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No bills yet.</p>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>