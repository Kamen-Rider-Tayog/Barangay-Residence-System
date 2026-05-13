<?php
require_once '../includes/init.php';
requireAdmin();

$household_id = $_GET['id'] ?? 0;

if (!$household_id) {
    header('Location: dashboard.php?error=invalid_id');
    exit();
}

// Delete household (cascade will delete residents and related records)
deleteData("DELETE FROM household WHERE household_id = ?", "i", [$household_id]);

header('Location: dashboard.php?success=household_deleted');
exit();
?>