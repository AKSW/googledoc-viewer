<?php

//email given in the project credentials
$client_email = '';

//p12 key downloaded from the project credentials
$private_key = file_get_contents('yourkey.p12');

//your password for the p12 private key
$privatekey_pass = '';

//Tags to be shown in the search formular
//Serach options are taken from the existing files
$searchTags = array ("type","status","supervisor");

//Tags to be displayed in the result table
//Title and Download Link are always displayed
// array("Tag"=>"defaultValue")
$displayTags = array(
    'status' => 'n.a.',
    'type' => 't.b.a.',
    'supervisor' => 'n.a.',
    );

// you usually don't have to change the lines below

//scopes to define the access, should work this way
$scopes = array('https://www.googleapis.com/auth/drive');
