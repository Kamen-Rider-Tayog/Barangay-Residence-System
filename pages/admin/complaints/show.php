<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$complaint_id = $_GET['id'] ?? 0;
if (!$complaint_id) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

$sql = "SELECT c.*, h.email, r.first_name, r.last_name 
        FROM complaint c
        JOIN household h ON c.household_id = h.household_id
        LEFT JOIN resident r ON h.household_id = r.household_id AND r.is_head = 1
        WHERE c.complaint_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $complaint_id);
$stmt->execute();
$complaint = $stmt->get_result()->fetch_assoc();

if (!$complaint) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="card detail-container">
        <div class="card-header">
            <h2><?php echo __('complaint-details'); ?></h2>
            <div>
                <a href="/barangay-residence-system/pages/admin/complaints/respond.php?id=<?php echo $complaint['complaint_id']; ?>" class="btn btn-primary"><?php echo __('respond'); ?></a>
                <a href="/barangay-residence-system/pages/dashboard.php" class="btn btn-outline"><?php echo __('back-to-dashboard'); ?></a>
            </div>
        </div>
        <div class="card-body">
            <div class="detail-section">
                <h3><?php echo __('complaint-information'); ?></h3>
                <table class="detail-table">
                    <tr><th><?php echo __('reference-no'); ?>:</th><td><?php echo htmlspecialchars($complaint['ref_no']); ?></td></tr>
                    <tr><th><?php echo __('subject'); ?>:</th><td><?php echo htmlspecialchars($complaint['subject']); ?></td></tr>
                    <tr><th><?php echo __('description'); ?>:</th><td><?php echo nl2br(htmlspecialchars($complaint['description'])); ?></td></tr>
                    <tr><th><?php echo __('category'); ?>:</th><td><?php echo ucfirst($complaint['category']); ?></td></tr>
                    <tr><th><?php echo __('priority'); ?>:</th><td><span class="badge badge-<?php echo $complaint['priority'] == 'high' ? 'red' : ($complaint['priority'] == 'medium' ? 'orange' : 'blue'); ?>"><?php echo ucfirst($complaint['priority']); ?></span></td></tr>
                    <tr><th><?php echo __('status'); ?>:</th><td><span class="badge badge-<?php echo $complaint['status'] == 'resolved' ? 'green' : ($complaint['status'] == 'pending' ? 'orange' : 'blue'); ?>"><?php echo ucfirst($complaint['status']); ?></span></a>
                    <tr><th><?php echo __('date-submitted'); ?>:</th><td><?php echo date('F d, Y h:i A', strtotime($complaint['date_submitted'])); ?></a>
                </table>
            </div>
            
            <div class="detail-section">
                <h3><?php echo __('resident-information'); ?></h3>
                <table class="detail-table">
                    <tr><th><?php echo __('name'); ?>:</th><td><?php echo htmlspecialchars($complaint['first_name'] . ' ' . $complaint['last_name']); ?></a>
                    <tr><th><?php echo __('email'); ?>:</th><td><?php echo htmlspecialchars($complaint['email']); ?></a>
                </table>
            </div>
            
            <?php if ($complaint['admin_response']): ?>
            <div class="detail-section">
                <h3><?php echo __('admin-response'); ?></h3>
                <div class="response-box">
                    <p><?php echo nl2br(htmlspecialchars($complaint['admin_response'])); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script src="/barangay-residence-system/assets/js/bootstrap.js"></script>