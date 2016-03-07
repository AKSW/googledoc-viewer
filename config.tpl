<?php

//email given in the project credentials
$client_email = '';

//p12 key downloaded from the project credentials
$private_key = file_get_contents('yourkey.p12');

//your password for the p12 private key
$privatekey_pass = '';

// you usually don't have to change the lines below

//scopes to define the access, should work this way
$scopes = array('https://www.googleapis.com/auth/drive');

?>
