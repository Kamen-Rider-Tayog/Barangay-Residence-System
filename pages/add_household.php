<?php
require_once '../includes/init.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit();
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$address = $_POST['address'] ?? '';
$phase_no = $_POST['phase_no'] ?? 'Phase 1';
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$contact = $_POST['contact'] ?? '';
$age = (int)($_POST['age'] ?? 0);

if (empty($email) || empty($password) || empty($address) || empty($first_name) || empty($last_name)) {
    header('Location: dashboard.php?error=missing_fields');
    exit();
}

// Check if email exists
$existing = getRow("SELECT household_id FROM household WHERE email = ?", "s", [$email]);
if ($existing) {
    header('Location: dashboard.php?error=email_exists');
    exit();
}

// Create household
$household_id = registerHousehold($email, $password, $address, $phase_no);

if (!$household_id) {
    header('Location: dashboard.php?error=insert_failed');
    exit();
}

// Add head resident
insertData("INSERT INTO resident (household_id, first_name, last_name, age, contact_no, is_head, relationship_to_head) VALUES (?, ?, ?, ?, ?, 1, 'Self')", 
           "issis", [$household_id, $first_name, $last_name, $age, $contact]);

header('Location: dashboard.php?success=household_added');
exit();
?>