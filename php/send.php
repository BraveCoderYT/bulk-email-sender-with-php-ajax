<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'phpmailer/autoload.php';

$name = $_POST['name'];
$from = $_POST['from'];
$reply_to = $_POST['reply-to'];
$subject = $_POST['subject'];
$msg = $_POST['msg'];

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'YOUR_EMAIL_ADDRESS';                     //SMTP username
    $mail->Password   = 'YOUR_PASSWORD';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($from, $name);
    foreach ($_POST['to'] as $key => $value) {
        $mail->addAddress($value);
    }
    
    if (!empty($reply_to)) {
        $mail->addReplyTo($reply_to);
    }

    //Attachments
    foreach ($_FILES['file']['name'] as $key => $value) {
        $target = "../uploads/".rand().$value;
        move_uploaded_file($_FILES['file']['tmp_name'][$key], $target);

        $mail->addAttachment($target);
    }

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    if (!empty($msg)) {
        $mail->Body = $msg;
    } else {
        $mail->Body = ' ';
    }

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}