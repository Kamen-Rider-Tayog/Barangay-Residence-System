<?php
require_once '../includes/init.php';

// Destroy all session data
session_destroy();

// Redirect to home page
header('Location: ' . SITE_URL . '/pages/index.php');
exit();
?>