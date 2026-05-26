<?php
require_once '../../../includes/core/init.php';
requireAdmin();

$type = $_GET['type'] ?? 'household';

if ($type === 'member') {
    $member_id = $_GET['id'] ?? 0;
    $household_id = $_GET['household_id'] ?? 0;
    
    if ($member_id) {
        $stmt = $conn->prepare("DELETE FROM resident WHERE resident_id = ? AND is_head = 0");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
    }
    
    header("Location: edit.php?id=" . $household_id);
    exit();
    
} else {
    $household_id = $_GET['id'] ?? 0;
    
    if (!$household_id) {
        header('Location: /barangay-residence-system/pages/dashboard.php');
        exit();
    }
    
    $conn->begin_transaction();
    
    try {
        $stmt1 = $conn->prepare("DELETE FROM resident WHERE household_id = ?");
        $stmt1->bind_param("i", $household_id);
        $stmt1->execute();
        
        $stmt2 = $conn->prepare("DELETE FROM household WHERE household_id = ?");
        $stmt2->bind_param("i", $household_id);
        $stmt2->execute();
        
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
    }
    
    header('Location: /barangay-residence-system/pages/dashboard.php?deleted=1');
    exit();
}
?>