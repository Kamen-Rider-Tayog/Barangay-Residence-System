<?php
function isLoggedIn() {
    return isset($_SESSION['household_id']) || isset($_SESSION['admin_id']);
}

function isAdmin() {
    return isset($_SESSION['admin_id']);
}

function isResident() {
    return isset($_SESSION['household_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /barangay-residence-system/pages/login.php');
        exit();
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /barangay-residence-system/pages/index.php');
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
?>