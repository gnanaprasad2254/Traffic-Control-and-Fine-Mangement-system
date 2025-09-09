<?php
include('../../includes/db.php');

if (isset($_GET['vehicle_no'])) {
    $vehicleNo = trim($_GET['vehicle_no']);

    $stmt = $conn->prepare("SELECT name, vehicle_no FROM user WHERE vehicle_no = ?");
    if ($stmt === false) {
        die('Error in prepare statement: ' . $conn->error);
    }

    $stmt->bind_param("s", $vehicleNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'name' => $row['name'], 'vehicle_no' => $row['vehicle_no']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Owner not found']);
    }
}
?>