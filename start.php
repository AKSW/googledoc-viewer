<?php

require_once 'google-api-php-client/trunk/src/Google/autoload.php'; // or wherever autoload.php is located
require_once 'config.php'; //loading project credentials
require_once 'functions.php';

$credentials = new Google_Auth_AssertionCredentials(
    $client_email,
    $scopes,
    $private_key,
    'notasecret',                                 // Default P12 password
    'http://oauth.net/grant_type/jwt/1.0/bearer', // Default grant type
    $user_to_impersonate
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
