<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$announcement_id = $_GET['id'] ?? 0;

if ($announcement_id) {
    $stmt = $conn->prepare("DELETE FROM announcements WHERE announcement_id = ?");
    $stmt->bind_param("i", $announcement_id);
    $stmt->execute();
}

header('Location: index.php');
exit();