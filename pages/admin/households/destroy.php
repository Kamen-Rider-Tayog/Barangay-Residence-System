<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$household_id = $_GET['id'] ?? 0;
if (!$household_id) {
    header('Location: /barangay-residence-system/pages/dashboard.php');
    exit();
}

$stmt = $conn->prepare("DELETE FROM household WHERE household_id = ?");
$stmt->bind_param("i", $household_id);
$stmt->execute();

header('Location: /barangay-residence-system/pages/dashboard.php?deleted=1');
exit();
?>