<?php
require_once '../../includes/core/init.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
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

if (empty($email) || empty($name) || empty($address) || empty($service_name)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$household = getRow("SELECT household_id FROM household WHERE email = ?", "s", [$email]);

if (!$household) {
    $default_password = 'password123';
    $household_id = registerHousehold($email, $default_password, $address, 'Phase 1');
    
    if (!$household_id) {
        echo json_encode(['success' => false, 'message' => 'Failed to create account']);
        exit();
    }
    
    $nameParts = explode(' ', $name, 2);
    $firstName = $nameParts[0];
    $lastName = $nameParts[1] ?? '';
    
    insertData("INSERT INTO resident (household_id, first_name, last_name, contact_no, is_head) VALUES (?, ?, ?, ?, 1)", 
               "isss", [$household_id, $firstName, $lastName, $contact]);
} else {
    $household_id = $household['household_id'];
}

$service = getRow("SELECT service_id, base_price FROM service WHERE service_name = ?", "s", [$service_name]);

if (!$service) {
    echo json_encode(['success' => false, 'message' => 'Service not found']);
    exit();
}

$request_id = createServiceRequest($household_id, $service['service_id'], $purpose, $delivery_method, $qty);

if ($request_id) {
    echo json_encode(['success' => true, 'message' => 'Request submitted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit request']);
}
exit();
?>