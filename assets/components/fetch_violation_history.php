<?php
include '../../includes/db.php';

if (isset($_GET['vehicle_no'])) {
    $vehicleNumber = $_GET['vehicle_no'];
    
    $query = "SELECT v.violation_date, r.violation_type, v.fine_amount, v.status, v.location 
              FROM violation v 
              JOIN rules r ON v.violation_type = r.rule_id 
              WHERE v.vehicle_number = ? 
              ORDER BY v.violation_date DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $vehicleNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $violations = [];
    while ($row = $result->fetch_assoc()) {
        $violations[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($violations);
    exit();
}

echo json_encode([]);
?>