<?php
namespace PHPMailer\PHPMailer;

require 'vendor/autoload.php';
require_once 'config.php';

use League\OAuth2\Client\Provider\Google;
use Hayageek\OAuth2\Client\Provider\Yahoo;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;
use Greew\OAuth2\Client\Provider\Azure;

session_start();

$providerName = '';
$clientId = '835049806007-kiff3gubmmf81l0r3j0eso9b6avr1pp2.apps.googleusercontent.com';
$clientSecret = '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';
$tenantId = '';
$flowname = '';

if (array_key_exists('provider', $_POST)) {
    $providerName = $_POST['provider'];
    $clientId = $_POST['clientId'];
    $clientSecret = $_POST['clientSecret'];
    $tenantId = $_POST['tenantId'];
    $_SESSION['provider'] = $providerName;
    $_SESSION['clientId'] = $clientId;
    $_SESSION['clientSecret'] = $clientSecret;
    $_SESSION['tenantId'] = $tenantId;
    $_SESSION['flowName'] = $flowName;
} elseif (array_key_exists('provider', $_SESSION)) {
    $providerName = $_SESSION['provider'];
    $clientId = $_SESSION['clientId'];
    $clientSecret = $_SESSION['clientSecret'];
    $tenantId = $_SESSION['tenantId'];
    $_SESSION['flowName'] = $flowName;
}

$redirectUri = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

$params = [
    'clientId' => $clientId,
    'clientSecret' => $clientSecret,
    'redirectUri' => $redirectUri,
    'accessType' => 'offline'
];

$options = [];
$provider = null;

switch ($providerName) {
    case 'Google':
        $provider = new Google($params);
        $options = [
            'scope' => [
                'https://mail.google.com/'
            ]
        ];
        break;
    case 'Yahoo':
        $provider = new Yahoo($params);
        break;
    case 'Microsoft':
        $provider = new Microsoft($params);
        $options = [
            'scope' => [
                'wl.imap',
                'wl.offline_access'
            ]
        ];
        break;
    case 'Azure':
        $params['tenantId'] = $tenantId;
        $provider = new Azure($params);
        $options = [
            'scope' => [
                'https://outlook.office.com/SMTP.Send',
                'offline_access'
            ]
        ];
        break;
}

if (null === $provider) {
    exit('Provider missing');
}

if (!isset($_GET['code'])) {
    $authUrl = $provider->getAuthorizationUrl($options);
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);
    exit;
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    unset($_SESSION['provider']);
    exit('Invalid state');
} else {
    unset($_SESSION['provider']);
    $token = $provider->getAccessToken(
        'authorization_code',
        [
            'code' => $_GET['code']
        ]
    );
    $db = new \DB();
    if ($db->is_table_empty()) {
        $db->update_refresh_token($token->getRefreshToken());
        echo "Refresh token inserted successfully.";
    }
}