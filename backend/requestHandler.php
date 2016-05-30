<?php
// suppress php warnings to generate clear json output
ini_set('display_errors','off');
require_once '../vendor/autoload.php';
//cors();
require_once 'serviceConfigs/configLoader.php'; //loading project credentials
require_once 'documentHandler/googleDriveHandler.php';

$displayTags = array(
    'status' => 'n.a.',
    'type' => 't.b.a.',
    'supervisor' => 'n.a.',
    'Test' => 'Test'
    );

$searchTags = array ("type","status","supervisor","Test");
//initializing documentHandler object
$documentHandler = new googleDriveHandler($configToken[0]);
if(isset($_GET['action']) && $_GET['action'] == "getTags"){
    $tags = array();
    $response = $documentHandler->getAllMetadata();
    foreach($searchTags as $tag){
        $tags[$tag] = $response[$tag];
    }
    echo json_encode($tags);
}elseif(isset($_GET['action']) && $_GET['action'] == "getMissingTags"){
    $constraints = buildConstraints($_GET);
    $result = $documentHandler->searchByDescription($constraints);
    $tags = $documentHandler->getTags($result);
    echo json_encode($tags);
}else{
    $constraints = buildConstraints($_GET);
    //searching files that match these constraints
    $result = $documentHandler->searchByMetadata($constraints);
    $response = array();
    if($result){
        foreach ($result as $x){
            $title = $documentHandler->getTitleById($x);
            $download = $documentHandler->getDownloadLink($documentHandler->searchById($x));
            $webContent = $documentHandler->getWebContentLink($documentHandler->searchById($x));
            $description = json_decode($documentHandler->getMetadataById($documentHandler->searchById($x)),true);
            //checking for regular document entry
            if($title && $download){
                $outputTagArray = array('title' => $title);
                //iterating over displayTags to gather information for the output
                $displayTagKeys = array_keys($displayTags);
                foreach ($displayTagKeys as $tag){
                    $outputTagArray[$tag] = $description[$tag]?$description[$tag]:$displayTags[$tag];
                }
                $outputTagArray['download'] = $download;
                $outputTagArray['webView'] = $webContent;
                array_push($response, $outputTagArray);
            }else{
                continue;
            }
        }
    }
    echo json_encode($response);
}
function isSerialized($str) {
    return ($str == serialize(false) || @unserialize($str) !== false);
}
function buildConstraints($data){
    //building file constraints from GET parameters
    $constraints = array();
    foreach($data as $key => $value){
        if(isSerialized($value))
            $value = unserialize($value)[0];//not 100% precise, we just take the first array value
        if($value != 'all' && $key != 'action' && $value != "")
            $constraints[$key] = $value;
    }
    return $constraints;
}
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
    if (isset($_SERVER['REQUEST_METHOD']) && (strtolower($_SERVER['REQUEST_METHOD']) == 'options')) {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        exit(0);
    }
}
