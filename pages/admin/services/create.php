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
        $error = 'Service name is required.';
    } else {
        $sql = "INSERT INTO service (service_name, base_price, description, is_active) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsi", $service_name, $base_price, $description, $is_active);
        
        if ($stmt->execute()) {
            $success = 'Service added successfully!';
            header('refresh:2;url=index.php');
        } else {
            $error = 'Failed to add service.';
        }
    }
}

include '../../../includes/layouts/header.php';
?>
<link rel="stylesheet" href="/barangay-residence-system/assets/css/pages/dashboard.css">
<?php include '../../../includes/layouts/navbar.php'; ?>

<main class="container">
    <div class="card">
        <div class="card-header">
            <h2>Add New Service</h2>
            <a href="index.php" class="btn btn-outline">Back to Services</a>
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
                    <label class="form-label">Service Name *</label>
                    <input type="text" name="service_name" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Price</label>
                    <input type="number" name="base_price" class="form-input" step="0.01" value="0.00">
                    <small class="form-hint">Set to 0 for free services</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="3" placeholder="Brief description of the service"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" checked> Active
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Service</button>
                    <a href="index.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../../../includes/layouts/footer.php'; ?>