<?php
require_once '../includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: services.php');
    exit();
}

$email = $_POST['email'] ?? '';
$name = $_POST['name'] ?? '';
$address = $_POST['address'] ?? '';
$contact = $_POST['contact'] ?? '';
$service_name = $_POST['service_name'] ?? '';
$qty = (int)($_POST['qty'] ?? 1);
$purpose = $_POST['purpose'] ?? '';
$payment_method = $_POST['payment_method'] ?? 'cash';
$delivery_method = $_POST['delivery_method'] ?? 'pickup';

// Validate required fields
if (empty($email) || empty($name) || empty($address) || empty($service_name) || empty($purpose)) {
    header('Location: services.php?error=missing_fields');
    exit();
}

// Check if household exists
$household = getRow("SELECT household_id FROM household WHERE email = ?", "s", [$email]);

if (!$household) {
    // Create new household
    $default_password = 'password123';
    $household_id = registerHousehold($email, $default_password, $address, 'Phase 1');
    
    if (!$household_id) {
        header('Location: services.php?error=registration_failed');
        exit();
    }
    
    // Add resident
    $nameParts = explode(' ', $name, 2);
    $firstName = $nameParts[0];
    $lastName = $nameParts[1] ?? '';
    
    insertData("INSERT INTO resident (household_id, first_name, last_name, contact_no, is_head) VALUES (?, ?, ?, ?, 1)", 
               "isss", [$household_id, $firstName, $lastName, $contact]);
} else {
    $household_id = $household['household_id'];
}

// Get service ID
$service = getRow("SELECT service_id, base_price FROM service WHERE service_name = ?", "s", [$service_name]);

if (!$service) {
    header('Location: services.php?error=service_not_found');
    exit();
}

// Create service request
$request_id = createServiceRequest($household_id, $service['service_id'], $purpose, $delivery_method, $qty);

if ($request_id) {
    header('Location: services.php?success=1');
} else {
    header('Location: services.php?error=request_failed');
}
exit();
?>