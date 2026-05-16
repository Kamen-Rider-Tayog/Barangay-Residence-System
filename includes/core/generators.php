<?php
function generateRefNo($prefix = 'BRG') {
    return $prefix . '-' . date('Ymd') . '-' . rand(1000, 9999);
}

function generateComplaintRefNo() {
    return 'CMP-' . date('Ymd') . '-' . rand(1000, 9999);
}
?>