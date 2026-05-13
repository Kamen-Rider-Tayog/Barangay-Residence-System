<?php
require_once '../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit();
}

$complaint_id = $_POST['complaint_id'] ?? 0;
$status = $_POST['status'] ?? 'pending';
$response = $_POST['response'] ?? '';

if (!$complaint_id) {
    header('Location: dashboard.php?error=invalid_complaint');
    exit();
}

$result = updateComplaintResponse($complaint_id, $status, $response);

if ($result !== false) {
    header('Location: dashboard.php?tab=complaints&success=1');
} else {
    header('Location: dashboard.php?tab=complaints&error=update_failed');
}
exit();
?>