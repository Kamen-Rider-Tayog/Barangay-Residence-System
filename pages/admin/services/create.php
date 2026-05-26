<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_name = trim($_POST['service_name'] ?? '');
    $base_price = (float)($_POST['base_price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($service_name)) {
        $error = __('service-name-required');
    } else {
        $sql = "INSERT INTO service (service_name, base_price, description, is_active) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsi", $service_name, $base_price, $description, $is_active);
        
        if ($stmt->execute()) {
            $success = __('service-added-success');
            header('refresh:2;url=/barangay-residence-system/pages/dashboard.php?tab=services');
        } else {
            $error = __('service-add-failed');
        }
    }
}

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="card form-container">
        <div class="card-header">
            <h2><?php echo __('add-new-service'); ?></h2>
            <a href="/barangay-residence-system/pages/dashboard.php?tab=services" class="btn btn-outline"><?php echo __('back-to-dashboard'); ?></a>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="error-alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-alert"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="service-form">
                <div class="form-group">
                    <label class="form-label"><?php echo __('service-name'); ?> *</label>
                    <input type="text" name="service_name" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label"><?php echo __('price'); ?></label>
                    <input type="number" name="base_price" class="form-input" step="0.01" value="0.00">
                    <small class="form-hint"><?php echo __('price-hint'); ?></small>
                </div>
                
                <div class="form-group">
                    <label class="form-label"><?php echo __('description'); ?></label>
                    <textarea name="description" class="form-input" rows="3" placeholder="<?php echo __('service-description-placeholder'); ?>"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" checked> <?php echo __('active'); ?>
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><?php echo __('save-service'); ?></button>
                    <a href="/barangay-residence-system/pages/dashboard.php?tab=services" class="btn btn-outline"><?php echo __('cancel'); ?></a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../../../includes/layouts/footer.php'; ?>