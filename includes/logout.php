<?php
session_start();
session_destroy();
header('Location: /barangay-residence-system/pages/index.php');
exit();
?>