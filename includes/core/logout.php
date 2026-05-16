<?php
require_once __DIR__ . '/init.php';
session_destroy();
header('Location: /barangay-residence-system/pages/index.php');
exit();
?>