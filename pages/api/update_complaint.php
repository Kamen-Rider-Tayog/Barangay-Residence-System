<?php
require_once '../../includes/core/init.php';
requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$complaint_id = $_POST['complaint_id'] ?? 0;
$status = $_POST['status'] ?? 'pending';
$response = $_POST['response'] ?? '';

if (!$complaint_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid complaint ID']);
    exit();
}

$result = updateComplaintResponse($complaint_id, $status, $response);

if ($result !== false) {
    echo json_encode(['success' => true, 'message' => 'Complaint updated']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update']);
}
exit();
?>