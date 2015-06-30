<?php

require('vendor/autoload.php');

require_once 'config.php'; //loading project credentials
require_once 'documentHandler.php';

$documentHandler = new documentHandler($client_email,$scopes,$private_key,$privatekey_pass,$grant,$user_to_impersonate);

$constraints = array();
foreach($_GET as $key => $value){
    if($value != 'all')
        $constraints[$key] = $value;     
}

$result = $documentHandler->searchByDescription($constraints);
if(!$result){
    echo "<p>Sorry, no Topic matched your criteria.</p>";
}else{

    $table = "<table>\n<tr><th>Titel</th><th>Downloadlink</th></tr>\n";
    foreach ($result as $x){
        $table .= "<tr><th>".$documentHandler->getTitleById($x)."</th>";
        $table .= "<th><a href=\"".$documentHandler->getDownloadLink($documentHandler->searchById($x))."\">Link</a></th></tr>\n";
    }
    $table .= "</table>\n";
}

echo $table;
