<?php
//ini_set('display_errors','on');
require_once 'vendor/autoload.php';
/**
 *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
 *  origin.
 *
 *  In a production environment, you probably want to be more restrictive, but this gives you
 *  the general idea of what is involved.  For the nitty-gritty low-down, read:
 *
 *  - https://developer.mozilla.org/en/HTTP_access_control
 *  - http://www.w3.org/TR/cors/
 *
 * source: http://stackoverflow.com/a/9866124/414075
 */
function cors() {
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}"); // unsafe, can allow session stealing
        //header("Acess-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    // Access-Control headers are received during OPTIONS requests
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        exit(0);
    }
}
cors();
require_once 'config.php'; //loading project credentials
require_once 'documentHandler.php';
//initializing documentHandler object
$documentHandler = new documentHandler($client_email,$scopes,$private_key,$privatekey_pass,$grant,$user_to_impersonate);
if(isset($_GET['action']) && $_GET['action'] == "getSupervisors"){
    $supervisors = $documentHandler->getAllSupervisors();
    echo json_encode($supervisors);
} else {
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
            $title = $documentHandler->getTitleById($x);
            $download = $documentHandler->getDownloadLink($documentHandler->searchById($x));
            if($title && $download){
                array_push($response, array('Title' => $title,'Download' => $download));
            }else{
                continue;
            }
        }
    }
    echo json_encode($response);
}
