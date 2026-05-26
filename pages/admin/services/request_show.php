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
                $message = __('status-updated-success');
                $messageType = "success";
            } else {
                $message = __('status-update-failed');
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
            $message = __('payment-updated-success');
            $messageType = "success";
        } else {
            $message = __('payment-update-failed');
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
            <h2><i class="fas fa-clipboard-list"></i> <?php echo __('service-request-details'); ?></h2>
            <a href="/barangay-residence-system/pages/dashboard.php?tab=services" class="btn-outline">
                <i class="fas fa-arrow-left"></i> <?php echo __('back-to-dashboard'); ?>
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
            <div class="card-header"><span class="font-bold"><?php echo __('request-information'); ?></span></div>
            <div class="card-body">
                <div class="detail-grid">
                    <div><div class="detail-label"><?php echo __('ref-no'); ?></div><div class="detail-value"><?php echo $request['ref_no']; ?></div></div>
                    <div><div class="detail-label"><?php echo __('date-submitted'); ?></div><div class="detail-value"><?php echo date('F d, Y h:i A', strtotime($request['date_submitted'])); ?></div></div>
                    <div><div class="detail-label"><?php echo __('delivery-method'); ?></div><div class="detail-value"><?php echo ucfirst($request['delivery_method']); ?></div></div>
                    <div><div class="detail-label"><?php echo __('purpose'); ?></div><div class="detail-value"><?php echo nl2br(htmlspecialchars($request['purpose'] ?? __('n-a'))); ?></div></div>
                </div>
            </div>
        </div>
        
        <!-- Resident Information Card -->
        <div class="card detail-card">
            <div class="card-header"><span class="font-bold"><?php echo __('resident-information'); ?></span></div>
            <div class="card-body">
                <div class="detail-grid">
                    <div><div class="detail-label"><?php echo __('resident'); ?></div><div class="detail-value"><?php echo $request['resident_info'] ?? __('n-a'); ?></div></div>
                    <div><div class="detail-label"><?php echo __('email'); ?></div><div class="detail-value"><?php echo $request['email']; ?></div></div>
                    <div><div class="detail-label"><?php echo __('address'); ?></div><div class="detail-value"><?php echo $request['address']; ?></div></div>
                    <div><div class="detail-label"><?php echo __('phase'); ?></div><div class="detail-value"><?php echo $request['phase_no'] ?? __('n-a'); ?></div></div>
                </div>
            </div>
        </div>
        
        <!-- Service & Payment Card -->
        <div class="card detail-card">
            <div class="card-header"><span class="font-bold"><?php echo __('service-payment-information'); ?></span></div>
            <div class="card-body">
                <div class="detail-grid">
                    <div><div class="detail-label"><?php echo __('service'); ?></div><div class="detail-value"><?php echo $request['service_name']; ?></div></div>
                    <div><div class="detail-label"><?php echo __('base-price'); ?></div><div class="detail-value">₱<?php echo number_format($request['base_price'], 2); ?></div></div>
                    <div><div class="detail-label"><?php echo __('total-amount'); ?></div><div class="detail-value">₱<?php echo number_format($request['total_amount'] ?? 0, 2); ?></div></div>
                    <div><div class="detail-label"><?php echo __('payment-method'); ?></div><div class="detail-value"><?php echo ucfirst($request['payment_method'] ?? __('n-a')); ?></div></div>
                </div>
            </div>
        </div>
        
        <!-- Update Status Form -->
        <div class="card detail-card">
            <div class="card-header"><span class="font-bold"><?php echo __('update-status'); ?></span></div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label"><?php echo __('current-status'); ?></label>
                        <select name="status" class="form-select">
                            <option value="pending" <?php echo $request['status'] == 'pending' ? 'selected' : ''; ?>><?php echo __('pending'); ?></option>
                            <option value="approved" <?php echo $request['status'] == 'approved' ? 'selected' : ''; ?>><?php echo __('approved'); ?></option>
                            <option value="processing" <?php echo $request['status'] == 'processing' ? 'selected' : ''; ?>><?php echo __('processing'); ?></option>
                            <option value="completed" <?php echo $request['status'] == 'completed' ? 'selected' : ''; ?>><?php echo __('completed'); ?></option>
                            <option value="rejected" <?php echo $request['status'] == 'rejected' ? 'selected' : ''; ?>><?php echo __('rejected'); ?></option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="update_status" class="btn-primary"><?php echo __('update-status'); ?></button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Update Payment Form -->
        <div class="card detail-card">
            <div class="card-header"><span class="font-bold"><?php echo __('update-payment'); ?></span></div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label"><?php echo __('payment-status'); ?></label>
                        <select name="is_paid" class="form-select">
                            <option value="0" <?php echo !$request['is_paid'] ? 'selected' : ''; ?>><?php echo __('unpaid'); ?></option>
                            <option value="1" <?php echo $request['is_paid'] ? 'selected' : ''; ?>><?php echo __('paid'); ?></option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="update_payment" class="btn-primary"><?php echo __('update-payment'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include '../../../includes/layouts/footer.php'; ?>