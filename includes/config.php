<?php
session_start();

// Database constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'barangay_system');

// Database connection with try-catch (MySQLi)
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // Don't expose sensitive info in production
    die("Database connection error. Please contact administrator.");
}

// For backward compatibility with old functions
function getConnection() {
    global $conn;
    return $conn;
}
?>