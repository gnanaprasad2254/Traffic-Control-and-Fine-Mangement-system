<?php

use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';
function contactEmail($email, $sub, $body)
{

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
    $mail->Body    = $body;
    $mail->send();
}
