<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$request_id = $_GET['id'] ?? 0;
if (!$request_id) {
    header('Location: /barangay-residence-system/pages/dashboard.php?tab=services');
    exit();
}

// Handle Status Update
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update Request Status
    if (isset($_POST['update_status'])) {
        $new_status = $_POST['status'] ?? '';
        $valid_statuses = ['pending', 'approved', 'processing', 'completed', 'rejected'];
        
        if (in_array($new_status, $valid_statuses)) {
            $updateSql = "UPDATE service_request SET status = ? WHERE request_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("si", $new_status, $request_id);
            if ($updateStmt->execute()) {
                $message = "Status updated successfully!";
                $messageType = "success";
            } else {
                $message = "Failed to update status.";
                $messageType = "error";
            }
        }
    }
    
    // Update Payment Status
    if (isset($_POST['update_payment'])) {
        $is_paid = $_POST['is_paid'] ?? 0;
        $paid_at = $is_paid ? date('Y-m-d H:i:s') : null;
        
        $updateSql = "UPDATE payment SET is_paid = ?, paid_at = ? WHERE request_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("isi", $is_paid, $paid_at, $request_id);
        if ($updateStmt->execute()) {
            $message = "Payment status updated!";
            $messageType = "success";
        } else {
            $message = "Failed to update payment.";
            $messageType = "error";
        }
    }
}

// Get request details
$sql = "SELECT sr.*, s.service_name, s.base_price,
        h.household_id, h.email, h.address, h.phase_no,
        p.payment_id, p.total_amount, p.payment_method, p.ref_no as payment_ref, p.is_paid, p.paid_at,
        (SELECT CONCAT(first_name, ' ', last_name, ' - ', contact_no) FROM resident WHERE household_id = sr.household_id AND is_head = 1 LIMIT 1) as resident_info
        FROM service_request sr
        JOIN service s ON sr.service_id = s.service_id
        JOIN household h ON sr.household_id = h.household_id
        LEFT JOIN payment p ON sr.request_id = p.request_id
        WHERE sr.request_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$request = $stmt->get_result()->fetch_assoc();

if (!$request) {
    header('Location: /barangay-residence-system/pages/dashboard.php?tab=services');
    exit();
}

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">

<style>
.detail-container { max-width: 800px; margin: 0 auto; }
.detail-card { margin-bottom: 1.5rem; }
.detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
.detail-label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: var(--gray-500); margin-bottom: 0.25rem; }
.detail-value { font-size: 0.9rem; font-weight: 500; color: var(--gray-800); }
.status-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 600; }
.status-pending { background: #fef3c7; color: #d97706; }
.status-approved { background: #dbeafe; color: #2563eb; }
.status-processing { background: #e9d5ff; color: #7e22ce; }
.status-completed { background: #d1fae5; color: #059669; }
.status-rejected { background: #fee2e2; color: #dc2626; }
.form-group { margin-bottom: 1.25rem; }
.form-label { display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--gray-700); }
.form-select, .form-input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--gray-300); border-radius: 0.5rem; font-size: 0.875rem; }
.form-actions { display: flex; gap: 1rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--gray-200); }
.btn-primary { background: var(--primary-blue); color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.5rem; cursor: pointer; }
.btn-primary:hover { background: var(--primary-blue-dark); }
.btn-outline { background: transparent; border: 1px solid var(--gray-300); padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; text-decoration: none; color: var(--gray-700); }
@media (max-width: 768px) { .detail-grid { grid-template-columns: 1fr; } }
</style>

<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="detail-container">
        <div class="flex-between mb-4">
            <h2><i class="fas fa-clipboard-list"></i> Service Request Details</h2>
            <a href="/barangay-residence-system/pages/dashboard.php?tab=services" class="btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <!-- Success/Error Messages -->
        <?php if ($message): ?>
            <div class="<?php echo $messageType === 'success' ? 'success-alert' : 'error-alert'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Request Information Card -->
        <div class="card detail-card">
            <div class="card-header"><span class="font-bold">Request Information</span></div>
            <div class="card-body">
                <div class="detail-grid">
                    <div><div class="detail-label">Reference No.</div><div class="detail-value"><?php echo $request['ref_no']; ?></div></div>
                    <div><div class="detail-label">Date Submitted</div><div class="detail-value"><?php echo date('F d, Y h:i A', strtotime($request['date_submitted'])); ?></div></div>
                    <div><div class="detail-label">Delivery Method</div><div class="detail-value"><?php echo ucfirst($request['delivery_method']); ?></div></div>
                    <div><div class="detail-label">Purpose</div><div class="detail-value"><?php echo nl2br(htmlspecialchars($request['purpose'] ?? 'N/A')); ?></div></div>
                </div>
            </div>
        </div>
        
        <!-- Resident Information Card -->
        <div class="card detail-card">
            <div class="card-header"><span class="font-bold">Resident Information</span></div>
            <div class="card-body">
                <div class="detail-grid">
                    <div><div class="detail-label">Resident</div><div class="detail-value"><?php echo $request['resident_info'] ?? 'N/A'; ?></div></div>
                    <div><div class="detail-label">Email</div><div class="detail-value"><?php echo $request['email']; ?></div></div>
                    <div><div class="detail-label">Address</div><div class="detail-value"><?php echo $request['address']; ?></div></div>
                    <div><div class="detail-label">Phase</div><div class="detail-value"><?php echo $request['phase_no'] ?? 'N/A'; ?></div></div>
                </div>
            </div>
        </div>
        
        <!-- Service & Payment Card -->
        <div class="card detail-card">
            <div class="card-header"><span class="font-bold">Service & Payment Information</span></div>
            <div class="card-body">
                <div class="detail-grid">
                    <div><div class="detail-label">Service</div><div class="detail-value"><?php echo $request['service_name']; ?></div></div>
                    <div><div class="detail-label">Base Price</div><div class="detail-value">₱<?php echo number_format($request['base_price'], 2); ?></div></div>
                    <div><div class="detail-label">Total Amount</div><div class="detail-value">₱<?php echo number_format($request['total_amount'] ?? 0, 2); ?></div></div>
                    <div><div class="detail-label">Payment Method</div><div class="detail-value"><?php echo ucfirst($request['payment_method'] ?? 'N/A'); ?></div></div>
                </div>
            </div>
        </div>
        
        <!-- Update Status Form -->
        <div class="card detail-card">
            <div class="card-header"><span class="font-bold">Update Status</span></div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Current Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" <?php echo $request['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo $request['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="processing" <?php echo $request['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="completed" <?php echo $request['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="rejected" <?php echo $request['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="update_status" class="btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Update Payment Form -->
        <div class="card detail-card">
            <div class="card-header"><span class="font-bold">Update Payment</span></div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Payment Status</label>
                        <select name="is_paid" class="form-select">
                            <option value="0" <?php echo !$request['is_paid'] ? 'selected' : ''; ?>>Unpaid</option>
                            <option value="1" <?php echo $request['is_paid'] ? 'selected' : ''; ?>>Paid</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="update_payment" class="btn-primary">Update Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include '../../../includes/layouts/footer.php'; ?>