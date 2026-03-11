<?php
require_once '../../includes/config/db.php';
require_once '../../includes/functions.php';
redirectIfNotLoggedIn();
if (!isPatient()) { header("Location: /medtrack/unauthorized.php"); exit; }

$user_id = $_SESSION['user_id'];
$patient_id = getPatientIdByUserId($pdo, $user_id);

// Handle add bill (demo)
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_bill'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $amount = floatval($_POST['amount']);
        $issue_date = $_POST['issue_date'];
        $stmt = $pdo->prepare("INSERT INTO bills (patient_id, amount, issue_date) VALUES (?, ?, ?)");
        if ($stmt->execute([$patient_id, $amount, $issue_date])) {
            $success = "Bill added.";
        } else {
            $error = "Failed to add bill.";
        }
    }
}

// Fetch bills
$stmt = $pdo->prepare("SELECT * FROM bills WHERE patient_id = ? ORDER BY issue_date DESC");
$stmt->execute([$patient_id]);
$bills = $stmt->fetchAll();

ob_start();
?>
<div class="container-fluid">
    <h2>Billing</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Demo: Add sample bill -->
    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#addBillForm">
        <i class="fas fa-plus"></i> Add Sample Bill (Demo)
    </button>
    <div class="collapse mb-4" id="addBillForm">
        <div class="card card-body">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
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

    <!-- Bills table -->
    <?php if ($bills): ?>
        <table class="table table-bordered" id="bills-table">
            <thead class="table-dark">
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
                <tr id="bill-row-<?php echo $bill['bill_id']; ?>">
                    <td><?php echo $bill['bill_id']; ?></td>
                    <td><?php echo $bill['issue_date']; ?></td>
                    <td>R<?php echo number_format($bill['amount'], 2); ?></td>
                    <td>R<?php echo number_format($bill['vat_amount'], 2); ?></td>
                    <td><strong>R<?php echo number_format($bill['total_amount'], 2); ?></strong></td>
                    <td class="bill-status"><?php echo $bill['status']; ?></td>
                    <td class="bill-paid-date"><?php echo $bill['paid_date'] ?? '-'; ?></td>
                    <td>
                        <?php if ($bill['status'] == 'unpaid'): ?>
                            <button class="btn btn-sm btn-success pay-bill" data-bill-id="<?php echo $bill['bill_id']; ?>">
                                <i class="fas fa-credit-card"></i> Pay Now
                            </button>
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

<script>
document.querySelectorAll('.pay-bill').forEach(btn => {
    btn.addEventListener('click', function() {
        const billId = this.dataset.billId;
        if (!confirm('Mark this bill as paid?')) return;
        fetch('/medtrack/api/pay_bill.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'bill_id=' + billId + '&csrf_token=<?php echo generateCSRFToken(); ?>'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById('bill-row-' + billId);
                row.querySelector('.bill-status').innerText = 'paid';
                row.querySelector('.bill-paid-date').innerText = data.paid_date;
                row.querySelector('.pay-bill').remove();
            } else {
                alert('Error: ' + data.error);
            }
        });
    });
});
</script>
<?php
$content_html = ob_get_clean();
$page_title = "Billing";
$current_page = 'billing';
include '../../includes/layout/layout_selector.php';
?>