<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']);
}

function isAdmin() {
    return isset($_SESSION['admin_id']);
}

function isResident() {
    return isset($_SESSION['household_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/pages/login.php');
        exit();
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ' . SITE_URL . '/pages/index.php');
        exit();
    }
}

function loginAdmin($username, $password) {
    global $conn;
    
    $sql = "SELECT admin_id, username, password FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['user_type'] = 'admin';
        return true;
    }
    return false;
}

function loginResident($email, $password) {
    global $conn;
    
    $sql = "SELECT household_id, email, password FROM household WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $household = $result->fetch_assoc();
    
    if ($household && password_verify($password, $household['password'])) {
        $_SESSION['household_id'] = $household['household_id'];
        $_SESSION['user_email'] = $household['email'];
        $_SESSION['user_type'] = 'resident';
        return true;
    }
    return false;
}

function registerHousehold($email, $password, $address, $phase_no) {
    global $conn;
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO household (email, password, address, phase_no) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $email, $hashed_password, $address, $phase_no);
    
    if ($stmt->execute()) {
        return $conn->insert_id;
    }
    return false;
}

function logout() {
    session_destroy();
    header('Location: ' . SITE_URL . '/pages/login.php');
    exit();
}

function generateRefNo($prefix = 'BRG') {
    return $prefix . '-' . date('Ymd') . '-' . rand(1000, 9999);
}

function generateComplaintRefNo() {
    return 'CMP-' . date('Ymd') . '-' . rand(1000, 9999);
}

function getServices() {
    global $conn;
    $sql = "SELECT * FROM service WHERE is_active = 1 ORDER BY service_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getDashboardStats() {
    global $conn;
    $stats = [];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM resident");
    $stmt->execute();
    $stats['total_residents'] = $stmt->get_result()->fetch_assoc()['total'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM household");
    $stmt->execute();
    $stats['total_households'] = $stmt->get_result()->fetch_assoc()['total'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM service_request");
    $stmt->execute();
    $stats['total_requests'] = $stmt->get_result()->fetch_assoc()['total'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM complaint WHERE status = 'pending'");
    $stmt->execute();
    $stats['pending_complaints'] = $stmt->get_result()->fetch_assoc()['total'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM resident WHERE is_voter = 1");
    $stmt->execute();
    $stats['total_voters'] = $stmt->get_result()->fetch_assoc()['total'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM service WHERE is_active = 1");
    $stmt->execute();
    $stats['total_services'] = $stmt->get_result()->fetch_assoc()['total'];
    
    return $stats;
}

function getHouseholdById($household_id) {
    global $conn;
    $sql = "SELECT * FROM household WHERE household_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $household_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getResidentsByHouseholdId($household_id) {
    global $conn;
    $sql = "SELECT * FROM resident WHERE household_id = ? ORDER BY is_head DESC, resident_id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $household_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getServiceRequestsByHouseholdId($household_id) {
    global $conn;
    $sql = "SELECT sr.*, s.service_name, s.base_price, p.is_paid, p.total_amount, p.payment_method
            FROM service_request sr
            JOIN service s ON sr.service_id = s.service_id
            LEFT JOIN payment p ON sr.request_id = p.request_id
            WHERE sr.household_id = ?
            ORDER BY sr.date_submitted DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $household_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getComplaintsByHouseholdId($household_id) {
    global $conn;
    $sql = "SELECT * FROM complaint WHERE household_id = ? ORDER BY date_submitted DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $household_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getRows($sql, $types = "", $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getRow($sql, $types = "", $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return null;
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function insertData($sql, $types = "", $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    if ($stmt->execute()) {
        return $conn->insert_id;
    }
    return false;
}

function updateData($sql, $types = "", $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->affected_rows;
}

function deleteData($sql, $types = "", $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->affected_rows;
}

function createServiceRequest($household_id, $service_id, $purpose, $delivery_method, $qty = 1) {
    global $conn;
    
    $conn->begin_transaction();
    
    try {
        $ref_no = generateRefNo();
        
        $sql1 = "INSERT INTO service_request (household_id, service_id, ref_no, purpose, delivery_method, status) 
                 VALUES (?, ?, ?, ?, ?, 'pending')";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("iisss", $household_id, $service_id, $ref_no, $purpose, $delivery_method);
        $stmt1->execute();
        $request_id = $conn->insert_id;
        
        $service = getRow("SELECT base_price FROM service WHERE service_id = ?", "i", [$service_id]);
        $total_amount = $service['base_price'] * $qty;
        
        $payment_ref = generateRefNo('PAY');
        $sql2 = "INSERT INTO payment (request_id, total_amount, payment_method, ref_no, is_paid) 
                 VALUES (?, ?, 'cash', ?, 0)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("ids", $request_id, $total_amount, $payment_ref);
        $stmt2->execute();
        
        $conn->commit();
        return $request_id;
        
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

function createComplaint($household_id, $subject, $description, $category, $priority) {
    global $conn;
    $ref_no = generateComplaintRefNo();
    $sql = "INSERT INTO complaint (household_id, ref_no, subject, description, category, priority, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    return insertData($sql, "isssss", [$household_id, $ref_no, $subject, $description, $category, $priority]);
}

function updateComplaintResponse($complaint_id, $status, $admin_response) {
    global $conn;
    $sql = "UPDATE complaint SET status = ?, admin_response = ? WHERE complaint_id = ?";
    return updateData($sql, "ssi", [$status, $admin_response, $complaint_id]);
}

function getAllComplaints() {
    global $conn;
    $sql = "SELECT c.*, h.email, r.first_name, r.last_name 
            FROM complaint c
            JOIN household h ON c.household_id = h.household_id
            LEFT JOIN resident r ON h.household_id = r.household_id AND r.is_head = 1
            ORDER BY c.date_submitted DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAllHouseholds() {
    global $conn;
    $sql = "SELECT h.*, COUNT(r.resident_id) as member_count,
            (SELECT CONCAT(first_name, ' ', last_name) FROM resident WHERE household_id = h.household_id AND is_head = 1 LIMIT 1) as head_name
            FROM household h 
            LEFT JOIN resident r ON h.household_id = r.household_id 
            GROUP BY h.household_id
            ORDER BY h.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getActiveCampaigns() {
    global $conn;
    $sql = "SELECT * FROM campaigns WHERE is_active = 1 AND end_date > NOW() ORDER BY end_date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getLatestCampaign() {
    global $conn;
    $sql = "SELECT * FROM campaigns WHERE is_active = 1 AND end_date > NOW() ORDER BY end_date ASC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getTopAnnouncement() {
    global $conn;
    $sql = "SELECT * FROM campaigns WHERE is_active = 1 AND end_date > NOW() ORDER BY end_date ASC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>