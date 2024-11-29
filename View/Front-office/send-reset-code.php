<?php
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $confirmation_code = rand(100000, 999999);

    // Save the confirmation code and email in the session or database
    session_start();
    $_SESSION['confirmation_code'] = $confirmation_code;
    $_SESSION['email'] = $email;

    // Send the email

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'Spiderspass@gmail.com';
    $mail->Password = 'SpidersPass25';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->SMTPDebug = 2;

    $mail->setFrom('Spiderspass@gmail.com', 'Spiders ©');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Confirmation Code';
    $mail->Body    = 'Your confirmation code is: ' . $confirmation_code;

    if ($mail->send()) {
        echo 'Confirmation code has been sent to your email.';
    } else {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}
?>