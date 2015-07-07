<?php

require('vendor/autoload.php');

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
if(!$result){
    $response = "<p>Sorry, no Topic matched your criteria.</p>";
}else{
    $response = "<table>\n<tr><th>Titel</th><th>Downloadlink</th></tr>\n";
    //filling the result html table
    foreach ($result as $x){
        $response .= "<tr><th>".$documentHandler->getTitleById($x)."</th>";
        $response .= "<th><a href=\"".$documentHandler->getDownloadLink($documentHandler->searchById($x))."\">Link</a></th></tr>\n";
    }
    $response .= "</table>\n";
}

echo $response;
