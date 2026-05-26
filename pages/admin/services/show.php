<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$service_id = $_GET['id'] ?? 0;
if (!$service_id) {
    header('Location: /barangay-residence-system/pages/dashboard.php?tab=services');
    exit();
}

$sql = "SELECT * FROM service WHERE service_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $service_id);
$stmt->execute();
$service = $stmt->get_result()->fetch_assoc();

if (!$service) {
    header('Location: /barangay-residence-system/pages/dashboard.php?tab=services');
    exit();
}

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="card form-container">
        <div class="card-header">
            <h2><?php echo __('service-details'); ?></h2>
            <div>
                <a href="edit.php?id=<?php echo $service_id; ?>" class="btn btn-primary"><?php echo __('edit'); ?></a>
                <a href="/barangay-residence-system/pages/dashboard.php?tab=services" class="btn btn-outline"><?php echo __('back-to-dashboard'); ?></a>
            </div>
        </div>
        <div class="card-body">
            <table class="detail-table">
                <tr><th><?php echo __('service-id'); ?>:</th><td><?php echo $service['service_id']; ?></td></tr>
                <tr><th><?php echo __('service-name'); ?>:</th><td><?php echo htmlspecialchars($service['service_name']); ?></td></tr>
                <tr><th><?php echo __('price'); ?>:</th><td><?php echo $service['base_price'] == 0 ? __('free') : '₱' . number_format($service['base_price'], 2); ?></td></tr>
                <tr><th><?php echo __('description'); ?>:</th><td><?php echo htmlspecialchars($service['description'] ?? __('no-description')); ?></td></tr>
                <tr><th><?php echo __('status'); ?>:</th><td><span class="badge badge-<?php echo $service['is_active'] ? 'green' : 'red'; ?>"><?php echo $service['is_active'] ? __('active') : __('inactive'); ?></span></td></tr>
                <tr><th><?php echo __('created'); ?>:</th><td><?php echo date('M d, Y h:i A', strtotime($service['created_at'] ?? 'now')); ?></td></tr>
            </table>
        </div>
    </div>
</main>