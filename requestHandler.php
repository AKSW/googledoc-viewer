<?php

require_once 'vendor/autoload.php';

require_once 'config.php'; //loading project credentials
require_once 'documentHandler.php';

//initializing documentHandler object
$documentHandler = new documentHandler($client_email,$scopes,$private_key,$privatekey_pass,$grant,$user_to_impersonate);

//building file constraints from GET parameters
$constraints = array();
foreach($_GET as $key => $value){
    if($value != 'all')
        $constraints[$key] = $value;     
}

//searching files that match these constraints
$result = $documentHandler->searchByDescription($constraints);
$response = array();
if($result){
    foreach ($result as $x){
        array_push($response, array(
            'Title' => $documentHandler->getTitleById($x),
            'Download' => $documentHandler->getDownloadLink($documentHandler->searchById($x))
            )
        );
    }
}

echo json_encode($response);
