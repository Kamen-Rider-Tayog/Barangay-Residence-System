<?php
function getRows($sql, $types = "", $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getRow($sql, $types = "", $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return null;
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function insertData($sql, $types = "", $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    if ($stmt->execute()) {
        return $conn->insert_id;
    }
    return false;
}

function updateData($sql, $types = "", $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->affected_rows;
}

function deleteData($sql, $types = "", $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->affected_rows;
}
?>