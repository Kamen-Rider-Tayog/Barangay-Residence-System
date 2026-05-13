<?php
require_once '../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit();
}

$household_id = $_POST['household_id'] ?? 0;
$address = $_POST['address'] ?? '';
$phase_no = $_POST['phase_no'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$contact = $_POST['contact'] ?? '';
$age = (int)($_POST['age'] ?? 0);

if (!$household_id) {
    header('Location: dashboard.php?error=invalid_id');
    exit();
}

// Update household
updateData("UPDATE household SET address = ?, phase_no = ? WHERE household_id = ?", "ssi", [$address, $phase_no, $household_id]);

// Update head resident
updateData("UPDATE resident SET first_name = ?, last_name = ?, contact_no = ?, age = ? WHERE household_id = ? AND is_head = 1", 
           "sssii", [$first_name, $last_name, $contact, $age, $household_id]);

header('Location: dashboard.php?success=household_updated');
exit();
?>