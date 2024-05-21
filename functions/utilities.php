<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function send_mail($to, $subject, $body)
{
    $email = "";
    $password = "";
    $first_name = "Nguyen";
    $last_name = "Khoa";
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $email;
        $mail->Password = $password;
        $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_SMTPS';
        $mail->Port = 587;
        $mail->setFrom($email, $first_name . ' ' . $last_name);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return $mail->ErrorInfo;
    }
}

function generateToken($length = 20)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $characters_length = strlen($characters);
    $output = '';
    for ($i = 0; $i < $length; $i++) {
        $output .= $characters[rand(0, $characters_length - 1)];
    }
    return $output;
}
