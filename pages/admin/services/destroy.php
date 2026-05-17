<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$service_id = $_GET['id'] ?? 0;
if (!$service_id) {
    header('Location: /barangay-residence-system/pages/dashboard.php?tab=services');
    exit();
}

// Check if service has existing requests
$check = $conn->prepare("SELECT COUNT(*) as count FROM service_request WHERE service_id = ?");
$check->bind_param("i", $service_id);
$check->execute();
$result = $check->get_result()->fetch_assoc();

if ($result['count'] > 0) {
    // Instead of delete, just deactivate
    $stmt = $conn->prepare("UPDATE service SET is_active = 0 WHERE service_id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $_SESSION['flash_message'] = 'Service has existing requests. It has been deactivated instead.';
} else {
    $stmt = $conn->prepare("DELETE FROM service WHERE service_id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $_SESSION['flash_message'] = 'Service deleted successfully.';
}

header('Location: /barangay-residence-system/pages/dashboard.php?tab=services');
exit();
?>