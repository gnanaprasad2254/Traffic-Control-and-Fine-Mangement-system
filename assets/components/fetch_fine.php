<?php
include('../../includes/db.php'); 

if(isset($_POST['rule_id'])) {
    $rule_id = intval($_POST['rule_id']);
    $query = "SELECT fine_amount FROM rules WHERE rule_id = $rule_id";
    $result = $conn->query($query);
    if($row = $result->fetch_assoc()) {
        echo $row['fine_amount'];
    } else {
        echo "0";
    }
}
?>
