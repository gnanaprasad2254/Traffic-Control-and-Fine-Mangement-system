<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<link rel="stylesheet" href="../src/output.css">

<body>

</body>

</html>
<?php
include '../includes/db.php';
include '../assets/components/email_sender.php';
include '../assets/components/photo_uploader.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['type'])) {
    $mode = $_POST['type'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact = $_POST['phone'];
    $address = $_POST['p_no'] . ", " . $_POST['strt'] . ", " . $_POST['stt'] . ", " . $_POST['cnt'];
    $desg = $_POST['desg'];
    $sta = $_POST['wstt'];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "SELECT * FROM `$mode` WHERE `email` = '$email' and `status` != 'passive'";
    $result = mysqli_query($conn, $sql);
    $id = "";
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('YOUR EMAIL IS ALREADY VERIFIED'); window.location.href = '/Fine-T/Interface/home.php';</script>";
    } else {
        $sql = "SELECT * FROM `$mode` WHERE `email` = '$email'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            if ($mode == "admin") {
                $desg = $desg . ' of ' . $sta;
                $sql = "INSERT INTO `$mode`(`name`,`password_hash`,`email`,`phone`,`designation`,`address`) VALUE('$name','$hash','$email','$contact','$desg','$address')";
            } else
                $sql = "INSERT INTO `$mode`(`name`,`password_hash`,`email`,`phone`,`badge_number`,`address`,`station_name`) VALUE('$name','$hash','$email','$contact','$desg','$address','$sta')";
            $result = mysqli_query($conn, $sql);
            $id = mysqli_insert_id($conn);
        }
        if(!empty($_FILES['idn']['tmp_name'])&&!empty($_FILES['prf']['tmp_name']))
        {
            $path = "../assets/images/$mode/$id";
            if (
                !uploadFile($_FILES['idn'], $path, "id") ||
                !uploadFile($_FILES['prf'], $path, "profile")
            ) {
    
                die("Failed to upload one or more files.");
            }
        };
        sendEmail($email, $name, 'Fine-T Sign Up for Admin', $email, 'email', $mode);
    }
}
?>