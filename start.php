<?php

require('vendor/autoload.php');

require_once 'config.php'; //loading project credentials
require_once 'documentHandler.php';

$documentHandler = new documentHandler($client_email,$scopes,$private_key,$privatekey_pass,$grant,$user_to_impersonate);

$constraints = array();
$constraints['status'] = "open";
$constraints['type'] = "thesis";

echo "using constraints:\n";
foreach ($constraints as $key => $value){
    echo "property : ".$key."=".$value."\n";
}

$result = $documentHandler->searchByDescription($constraints);
if(!$result){
    echo "I found nothing.\n";
}else{

    foreach ($result as $x){
        echo "I found a Document: ".$x."\n";
        echo "Download at: ".$documentHandler->getDownloadLink($documentHandler->searchById($x))."\n";
    }
}
