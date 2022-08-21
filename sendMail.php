<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require_once('BackEnd/ConnectionDB/DB_classes.php');

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                   
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                   
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'ngantv2k@gmail.com';                    
    $mail->Password   = 'ngaN1810!';                              
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       
    $mail->Port       = 587;
    //Recipients
    $mail->setFrom('ngantv2k@gmail.com', 'Admin');
    $mail->addAddress('nganvu18102k@gmail.com', 'Ngan Vu');

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';

    $mail->send();
    echo 'Message has been sent';
    die (json_encode(true));
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    die (json_encode($e));
}
