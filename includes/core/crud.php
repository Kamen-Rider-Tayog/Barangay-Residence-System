<?php
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
?>