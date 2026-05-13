<?php
require_once '../includes/init.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: user.php');
    exit();
}

$subject = $_POST['subject'] ?? '';
$category = $_POST['category'] ?? '';
$priority = $_POST['priority'] ?? 'medium';
$description = $_POST['description'] ?? '';

if (empty($subject) || empty($category) || empty($description)) {
    header('Location: user.php?error=missing_fields');
    exit();
}

$household_id = $_SESSION['household_id'];

$complaint_id = createComplaint($household_id, $subject, $description, $category, $priority);

if ($complaint_id) {
    header('Location: user.php?success=complaint_submitted');
} else {
    header('Location: user.php?error=complaint_failed');
}
exit();
?>