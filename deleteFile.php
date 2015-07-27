<?php
/* script for deleting a single file from the google drive
 * e.g. when you can't access it via drive gui
 * php deleteFile.php -f "How to get started with Drive" deletes this file
 */  
require_once 'vendor/autoload.php';

require_once 'config.php'; //loading project credentials
require_once 'documentHandler.php';

//initializing documentHandler object
$documentHandler = new documentHandler($client_email,$scopes,$private_key,$privatekey_pass,$grant,$user_to_impersonate);

$filename = getopt("f:")["f"];

echo "deleting file \"".$filename."\"\n";
$id = $documentHandler->searchByTitle($filename);
if($id){
    echo "found \"".$filename."\" with id = ".$id."\n deleting... \n";
    $status = $documentHandler->deleteFile($id);
    if($status == false){
        echo "ok\n";
    }else{
        echo "something went wrong, error message = ".$status."\n";
    }
}else{
    echo "sorry, can't find that file\n";
}

