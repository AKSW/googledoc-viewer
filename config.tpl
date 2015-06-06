<?php

$user_to_impersonate = 'example_user@example.de'; //owner of the google drive folder you want to access
$client_email = '';//email given in the project credentials
$private_key = file_get_contents('yourkey.p12');//p12 key downloaded from the project credentials
$scopes = array('https://www.googleapis.com/auth/drive');//scopes to define the access, should work this way
$privatekey_pass = "";//your password for the p12 private key

?>
