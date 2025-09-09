<?php
header('Content-Type: application/json; charset=UTF-8');
include '../includes/db.php';

try {
    $searchType = $_GET['type'] ?? '';
    $searchValue = $_GET['cred'] ?? '';

    if (empty($searchType)) {
        throw new Exception("Search type not specified");
    }

    if (empty($searchValue)) {
        throw new Exception("Search value not provided");
    }

    switch ($searchType) {
        case 'v_no':
            $query = "SELECT v.*, u.name as user_name, u.email, u.phone, r.violation_type, r.description, r.rule_number 
                      FROM violation v 
                      JOIN user u ON v.user_id = u.user_id 
                      JOIN rules r ON v.violation_type = r.rule_id 
                      WHERE v.vehicle_number = ?";
            break;
        case 'l_no':
            $query = "SELECT v.*, u.name as user_name, u.email, u.phone, r.violation_type, r.description, r.rule_number 
                      FROM violation v 
                      JOIN user u ON v.user_id = u.user_id 
                      JOIN rules r ON v.violation_type = r.rule_id 
                      WHERE u.license = ?";
            break;
        case 'c_no':
            $query = "SELECT v.*, u.name as user_name, u.email, u.phone, r.violation_type, r.description, r.rule_number 
                      FROM violation v 
                      JOIN user u ON v.user_id = u.user_id 
                      JOIN rules r ON v.violation_type = r.rule_id 
                      WHERE v.chassis_number = ?";
            break;
        default:
            throw new Exception("Invalid search type");
    }
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $searchValue);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $challans = [];
    
    while ($row = $result->fetch_assoc()) {
        $challans[] = $row;
    }
    echo json_encode([
        'success' => true,
        'data' => $challans
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
    ob_end_flush();
}
?>