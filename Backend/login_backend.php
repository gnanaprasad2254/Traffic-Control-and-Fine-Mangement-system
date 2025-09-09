<?php
include '../includes/db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mode = $_POST['mode'];
    $pass = $_POST['password'];
    $iden = $_POST['iden'];
    if ($pass === "" || $iden === "") {
        if ($mode == 'officer')
            echo "<script>alert('You have not entered the details properly!!!Redirecting...'); window.location.href = '/Fine-T/Interface/officer_login.php';</script>";
        else {
            echo "<script>alert('You have not entered the details properly!!!Redirecting...'); window.location.href = '/Fine-T/Interface/admin_login.php';</script>";
        }
    } else {
        if ($mode == 'officer')
            $sql = "SELECT * From `$mode` WHERE `badge_number`='$iden' and `status` = 'active'";
        else
            $sql = "SELECT * From `$mode` WHERE `email`='$iden' and `status` = 'active'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            echo "<script>alert('You have not registered!!!Redirecting...'); window.location.href = '/Fine-T/Interface/sign_up.php';</script>";
        } else {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($pass, $row['password_hash'])) {
                $_SESSION['username'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['mode'] = $mode;
                if ($mode == 'officer') {
                    $_SESSION['b_no'] = $row['badge_number'];
                    echo "<script>alert('You have logged in !!!Redirecting...'); window.location.href = '/Fine-T/Interface/officer_dashboard.php';</script>";
                } else {
                    echo "<script>alert('You have logged in !!!Redirecting...'); window.location.href = '/Fine-T/Interface/admin_functions/admin_dashboard.php';</script>";
                }
            } else {
                if ($mode == 'officer')
                    echo "<script>alert('Invalid!!!Redirecting...'); window.location.href = '/Fine-T/Interface/officer_login.php';</script>";
                else {
                    echo "<script>alert('Invalid!!!Redirecting...'); window.location.href = '/Fine-T/Interface/admin_login.php';</script>";
                }
            }
        }
    }
}
