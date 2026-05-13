<?php
require_once '../includes/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Try admin login first
    if (loginAdmin($email, $password)) {
        header('Location: dashboard.php');
        exit();
    }
    
    // Try resident login
    if (loginResident($email, $password)) {
        header('Location: user.php');
        exit();
    }
    
    // Login failed
    $_SESSION['login_error'] = 'Invalid email/username or password';
    header('Location: login.php');
    exit();
}
?>