<?php
function getServices() {
    global $conn;
    $sql = "SELECT * FROM service WHERE is_active = 1 ORDER BY service_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getDashboardStats() {
    global $conn;
    $stats = [];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM resident");
    $stmt->execute();
    $stats['total_residents'] = $stmt->get_result()->fetch_assoc()['total'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM household");
    $stmt->execute();
    $stats['total_households'] = $stmt->get_result()->fetch_assoc()['total'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM service_request");
    $stmt->execute();
    $stats['total_requests'] = $stmt->get_result()->fetch_assoc()['total'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM complaint WHERE status = 'pending'");
    $stmt->execute();
    $stats['pending_complaints'] = $stmt->get_result()->fetch_assoc()['total'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM resident WHERE is_voter = 1");
    $stmt->execute();
    $stats['total_voters'] = $stmt->get_result()->fetch_assoc()['total'];
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM service WHERE is_active = 1");
    $stmt->execute();
    $stats['total_services'] = $stmt->get_result()->fetch_assoc()['total'];
    
    return $stats;
}

function getHouseholdById($household_id) {
    global $conn;
    $sql = "SELECT * FROM household WHERE household_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $household_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getResidentsByHouseholdId($household_id) {
    global $conn;
    $sql = "SELECT * FROM resident WHERE household_id = ? ORDER BY is_head DESC, resident_id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $household_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getServiceRequestsByHouseholdId($household_id) {
    global $conn;
    $sql = "SELECT sr.*, s.service_name, s.base_price, p.is_paid, p.total_amount, p.payment_method
            FROM service_request sr
            JOIN service s ON sr.service_id = s.service_id
            LEFT JOIN payment p ON sr.request_id = p.request_id
            WHERE sr.household_id = ?
            ORDER BY sr.date_submitted DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $household_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getComplaintsByHouseholdId($household_id) {
    global $conn;
    $sql = "SELECT * FROM complaint WHERE household_id = ? ORDER BY date_submitted DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $household_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAllComplaints() {
    global $conn;
    $sql = "SELECT c.*, h.email, r.first_name, r.last_name 
            FROM complaint c
            JOIN household h ON c.household_id = h.household_id
            LEFT JOIN resident r ON h.household_id = r.household_id AND r.is_head = 1
            ORDER BY c.date_submitted DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAllHouseholds() {
    global $conn;
    $sql = "SELECT 
                h.*, 
                COUNT(r.resident_id) as member_count,
                COALESCE(
                    (SELECT CONCAT(first_name, ' ', last_name) FROM resident WHERE household_id = h.household_id AND is_head = 1 LIMIT 1),
                    (SELECT CONCAT(first_name, ' ', last_name) FROM resident WHERE household_id = h.household_id LIMIT 1),
                    'Unnamed'
                ) as head_name,
                COALESCE(
                    (SELECT contact_no FROM resident WHERE household_id = h.household_id AND is_head = 1 LIMIT 1),
                    (SELECT contact_no FROM resident WHERE household_id = h.household_id LIMIT 1),
                    'N/A'
                ) as contact_no
            FROM household h 
            LEFT JOIN resident r ON h.household_id = r.household_id 
            GROUP BY h.household_id
            ORDER BY h.household_id DESC";  
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAllPhases() {
    global $conn;
    $sql = "SELECT DISTINCT phase_no FROM household WHERE phase_no IS NOT NULL AND phase_no != '' ORDER BY phase_no";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $phases = [];
    while ($row = $result->fetch_assoc()) {
        $phases[] = $row['phase_no'];
    }
    return $phases;
}
?>