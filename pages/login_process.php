<?php
require_once '../includes/core/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (loginAdmin($email, $password)) {
        header('Location: dashboard.php');
        exit();
    }
    
    if (loginResident($email, $password)) {
        header('Location: user.php');
        exit();
    }
    
    header('Location: login.php?error=1');
    exit();
}
?>