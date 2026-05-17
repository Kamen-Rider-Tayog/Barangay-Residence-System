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
        $sql = "UPDATE service SET service_name = ?, base_price = ?, description = ?, is_active = ? WHERE service_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsii", $service_name, $base_price, $description, $is_active, $service_id);
        
        if ($stmt->execute()) {
            $success = 'Service updated successfully!';
            $service['service_name'] = $service_name;
            $service['base_price'] = $base_price;
            $service['description'] = $description;
            $service['is_active'] = $is_active;
        } else {
            $error = 'Failed to update service.';
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
            <h2>Edit Service</h2>
            <a href="/barangay-residence-system/pages/dashboard.php?tab=services" class="btn btn-outline">Back to Dashboard</a>
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
                    <input type="text" name="service_name" class="form-input" value="<?php echo htmlspecialchars($service['service_name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Price</label>
                    <input type="number" name="base_price" class="form-input" step="0.01" value="<?php echo $service['base_price']; ?>">
                    <small class="form-hint">Set to 0 for free services</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="3"><?php echo htmlspecialchars($service['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?php echo $service['is_active'] ? 'checked' : ''; ?>> Active
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="/barangay-residence-system/pages/dashboard.php?tab=services" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>
