<?php
// suppress php warnings to generate clear json output
ini_set('display_errors','off');
require_once __DIR__.'/config.php';
require_once __DIR__.'/../vendor/autoload.php';
cors();
require_once 'serviceConfigs/configLoader.php'; //loading project credentials
foreach (scandir(__DIR__.'/documentHandler') as $filename) {
    $path = __DIR__ . '/documentHandler/' . $filename;
    if (is_file($path)) {
        require_once $path;
    }
}

//initializing documentHandler object
$documentHandler = new documentHandlerMain($configToken);
if(isset($_GET['action']) && $_GET['action'] == "getTags"){
    $tags = array();
    $response = $documentHandler->getAllMetadata();
    foreach($searchTags as $tag){
        $tags[$tag] = $response[$tag];
    }
    echo json_encode($tags);
}elseif(isset($_GET['action']) && $_GET['action'] == "getMissingTags"){
    $constraints = buildConstraints($_GET);
    $result = $documentHandler->searchByMetadata($constraints);
    $tags = $documentHandler->getAllMetadata($result);
    echo json_encode($tags);
}else{
    mylog('Filter and retrieve files!');
    mylog('First get the constraints:');
    $constraints = buildConstraints($_GET);
    mylog($constraints);
    //searching files that match these constraints
    mylog('Now filter the files by the constraints');
    $result = $documentHandler->searchByMetadata($constraints);
    mylog('filter returns:');
    mylog($result);
    $response = array();
    if($result){
        mylog('Now go through each file and extract the data');
        foreach ($result as $x){
            $title = $documentHandler->getTitleById($x);
            $download = $documentHandler->getDownloadLink($x);
            $webContent = $documentHandler->getWebContentLink($x);
            $description = json_decode($documentHandler->getMetadataById($x),true);
            //checking for regular document entry
            if($title && $download){
                //iterating over displayTags to gather information for the output
                $displayTagKeys = array_keys($displayTags);
                foreach ($displayTagKeys as $tag){
                    if($tag == 'title'){
                        $outputTagArray['title'] = $title; 
                        continue;
                    }elseif($tag == 'download'){
                        $outputTagArray['download'] = $download;
                        continue;
                    }elseif($tag == 'webView'){
                        $outputTagArray['webView'] = $webContent;
                        continue;
                    }
                    $outputTagArray[$tag] = $description[$tag]?$description[$tag]:$displayTags[$tag];
                }

                array_push($response, $outputTagArray);
            }else{
                mylog('No title and download found for:');
                mylog($x);
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

function mylog($message) {
  return;
  print_r($message);
  print "\n";
}
