<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$announcement_id = $_GET['id'] ?? 0;
if (!$announcement_id) {
    header('Location: /barangay-residence-system/pages/dashboard.php?tab=announcements');
    exit();
}

$stmt = $conn->prepare("DELETE FROM announcements WHERE announcement_id = ?");
$stmt->bind_param("i", $announcement_id);

if ($stmt->execute()) {
    $_SESSION['flash_message'] = 'Announcement deleted successfully.';
    $_SESSION['flash_type'] = 'success';
} else {
    $_SESSION['flash_message'] = 'Failed to delete announcement.';
    $_SESSION['flash_type'] = 'error';
}

header('Location: /barangay-residence-system/pages/dashboard.php?tab=announcements');
exit();
?>