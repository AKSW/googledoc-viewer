<?php

require_once 'google-api-php-client/trunk/src/Google/autoload.php'; // or wherever autoload.php is located
require_once 'config.php'; //loading project credentials
require_once 'functions.php';

$credentials = new Google_Auth_AssertionCredentials(
    $client_email, //service account email adress
    $scopes,
    $private_key, //P12 key downloaded from project credentials
    $privatekey_pass,                          
    'http://oauth.net/grant_type/jwt/1.0/bearer', // Default grant type
    $user_to_impersonate // email adress
);

$client = new Google_Client();
$client->setAssertionCredentials($credentials);
if ($client->getAuth()->isAccessTokenExpired()) {
    $client->getAuth()->refreshTokenWithAssertion();
}

$service = new Google_Service_Drive($client);

foreach (retrieveAllFiles($service) as $file){
    print($file->getTitle()."\n");
}

?>
