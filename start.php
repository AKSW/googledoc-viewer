<?php

require('vendor/autoload.php');

require_once 'config.php'; //loading project credentials
require_once 'documentHandler.php';

$documentHandler = new documentHandler($client_email,$scopes,$private_key,$privatekey_pass,$grant,$user_to_impersonate);


