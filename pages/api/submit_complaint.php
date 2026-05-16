<?php
require_once '../../includes/core/init.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$subject = $_POST['subject'] ?? '';
$category = $_POST['category'] ?? '';
$priority = $_POST['priority'] ?? 'medium';
$description = $_POST['description'] ?? '';

if (empty($subject) || empty($category) || empty($description)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
    exit();
}

$household_id = $_SESSION['household_id'];

$complaint_id = createComplaint($household_id, $subject, $description, $category, $priority);

if ($complaint_id) {
    echo json_encode(['success' => true, 'message' => 'Complaint submitted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']);
}
exit();
?>