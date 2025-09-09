<?php
session_name("superadmin");
include '../includes/db.php';
include '../assets/components/contact_mail.php';

if (!isset($_SESSION['superadmin']) || $_SESSION['superadmin'] !== true) {
    http_response_code(403);
    echo "<h2>Access Denied.</h2>";
    exit;
}
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ad_id = $_POST['admin_id'];
    $sub = "ADMIN APPROVAL PROCESS";
    $sql = "SELECT `email`, `name` FROM `admin` WHERE `admin_id` = '$ad_id'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    if($_POST['action'] == 'approve')
    {
        $sql = "UPDATE `admin` set `status` = 'active' WHERE `admin_id` = '$ad_id' ";
        $body = "Dear Officer $name,<br>Your application for the positon of a admin in the E-Fine is <b>Approved</b>.<br> Thank You.";
    }
    else
    {
        $sql = "DELETE FROM `admin` WHERE `admin_id` = '$ad_id'";
        $path = '../assets/images/admin/'.$ad_id.'';
        rmdir($path);
        $body = "Dear Officer $name,<br>Your application for the positon of a admin in the E-Fine is <b>Rejected</b>.<br> Thank You.Contact Concerned higher Officer";
    }
    mysqli_query($conn,$sql);
    $email = $row['email'];
    contactEmail($email,$sub,$body);
    header("Location: ../Interface/_verify_admins_x91h.php");
    exit();
}
?>