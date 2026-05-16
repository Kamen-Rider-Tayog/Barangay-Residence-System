<?php
require_once '../../includes/core/init.php';
requireAdmin();

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;
if (!$id) {
    echo json_encode(['error' => 'No ID provided']);
    exit();
}

$sql = "SELECT c.*, h.email, r.first_name, r.last_name 
        FROM complaint c
        JOIN household h ON c.household_id = h.household_id
        LEFT JOIN resident r ON h.household_id = r.household_id AND r.is_head = 1
        WHERE c.complaint_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$complaint = $result->fetch_assoc();

if ($complaint) {
    echo json_encode($complaint);
} else {
    echo json_encode(['error' => 'Complaint not found']);
}
?>