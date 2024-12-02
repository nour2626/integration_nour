<?php
require '../../vendor/autoload.php';
require_once 'include/Oauth.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use League\OAuth2\Client\Provider\Google;

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $confirmation_code = rand(100000, 999999);

    // Save the confirmation code and email in the session
    $_SESSION['confirmation_code'] = $confirmation_code;
    $_SESSION['email'] = $email;

    // Load the OAuth2 token for the user
    $db = new OAuth();
    $user_id = 1;  // Example: you can get the user_id based on session or login
    $oauth = $db->get_oauth($user_id);

    // If no OAuth token found, generate a new one
    if (!$oauth) {
        // Set up the OAuth2 provider (Google in this case)
        $provider = new Google([
            'clientId' => '43411978604-1r8u16ou5in4aeh6qju4n554r6od903t.apps.googleusercontent.com',
            'clientSecret' => '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',
            'redirectUri' => 'http://your-redirect-uri',
        ]);

        // Generate a new token
        $authUrl = $provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $provider->getState();
        header('Location: ' . $authUrl);
        exit;
    }

    // Set up the OAuth2 provider (Google in this case)
    $provider = new Google([
        'clientId' => '43411978604-1r8u16ou5in4aeh6qju4n554r6od903t.apps.googleusercontent.com',
        'clientSecret' => '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',
        'redirectUri' => 'http://your-redirect-uri',
    ]);

    // Get a new access token using the refresh token
    $newToken = $provider->getAccessToken('refresh_token', [
        'refresh_token' => $oauth
    ]);

    // Send the email with confirmation code
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->AuthType = 'XOAUTH2';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->SMTPDebug = 2;

    $mail->setOAuth(new \PHPMailer\PHPMailer\OAuth([
        'provider' => $provider,
        'clientId' => '43411978604-1r8u16ou5in4aeh6qju4n554r6od903t.apps.googleusercontent.com',
        'clientSecret' => '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',
        'refreshToken' => $oauth,
        'userName' => 'Spiderspass@gmail.com',
    ]));

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