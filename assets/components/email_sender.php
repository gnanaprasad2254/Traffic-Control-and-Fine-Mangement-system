<?php

use PHPMailer\PHPMailer\PHPMailer;
include '../assets/components/otp_verify.php';
require '../vendor/autoload.php';
function sendEmail($email,$name, $sub, $verify_subject, $verify_field,$table)
{
    $otp = "";
    for ($i = 0; $i < 4; $i++) {
        $otp = $otp . rand(0, 9);
    }
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'sidddareddyrajeshreddy@gmail.com';
    $mail->Password   = 'epmrpivxwotkbnfy';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('sidddareddyrajeshreddy@gmail.com');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = $sub;
    $mail->Body    = 'Greetings, <b>' . $name . '</b><br><br>This is your OTP: ' . $otp . "<br>Thank You";
    $array = [$otp, $verify_subject,$verify_field,$table];
    $string = implode(",", $array);
    if ($mail->send()) {
        
        takeOtp($string);
    }
}
