<?php
require_once '../../includes/core/init.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$subject = trim($_POST['subject'] ?? '');
$category = trim($_POST['category'] ?? '');
$priority = trim($_POST['priority'] ?? 'medium');
$description = trim($_POST['description'] ?? '');

if (empty($subject)) {
    echo json_encode(['success' => false, 'message' => 'Subject is required']);
    exit();
}

if (empty($category)) {
    echo json_encode(['success' => false, 'message' => 'Category is required']);
    exit();
}

if (empty($description)) {
    echo json_encode(['success' => false, 'message' => 'Description is required']);
    exit();
}

$household_id = $_SESSION['household_id'];

if (!$household_id) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in']);
    exit();
}

// Generate reference number
$ref_no = 'CMP-' . date('Ymd') . '-' . rand(1000, 9999);

// Use the existing createComplaint function
$complaint_id = createComplaint($household_id, $subject, $description, $category, $priority);

if ($complaint_id) {
    echo json_encode(['success' => true, 'message' => 'Complaint submitted successfully', 'ref_no' => $ref_no]);
} else {
    // Get the actual error
    global $conn;
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}
exit();
?>