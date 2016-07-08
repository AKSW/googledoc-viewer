<?php

require_once 'abstractDocumentHandler.php';
/**
 * class for providing methods on the documents of a google drive
 *
 *
 */
class googleDriveHandler extends abstractDocumentHandler{
    private $credentials;
    private $client;
    private $service;
    private $files;
    /**
     * instantiate every object with a working google api service
     *
     * @param $client_email string service account email adress
     * @param $scopes string uri of the scopes
     * @param $private_key string path to the P12 key file downloaded from project credentials
     * @param $privatekey_pass string password of the P12 key
     * @param $grant string uri of the grant type
     */
    public function __construct($configToken){
        $client_email = $configToken['client_email'];
        $scopes = array($configToken['scope']);
        $private_key = file_get_contents($configToken['private_key']);
        $privatekey_pass = $configToken['privatekey_pass'];
        $grant = 'http://oauth.net/grant_type/jwt/1.0/bearer';
        $this->credentials = new Google_Auth_AssertionCredentials($client_email, $scopes, $private_key, $privatekey_pass, $grant);
        $config = new Google_Config();
        $config->setClassConfig('Google_Cache_File', array('directory' => '/tmp/cache'));
        $this->client = new Google_Client($config);
        $this->client->setAssertionCredentials($this->credentials);
        if ($this->client->getAuth()->isAccessTokenExpired()) {
            $this->client->getAuth()->refreshTokenWithAssertion();
        }
        $this->service = new Google_Service_Drive($this->client);
        $this->files = $this->retrieveAllFiles($this->service);
    }
    /**
     * searches all files for description tags and collects entries
     * @return: array of all metadata tags
     */
    public function getAllMetadata($sublist = NULL){
        if($sublist){
            $tmpFilesList = array();
            foreach($sublist as $tmpId){
               array_push($tmpFilesList,$this->searchById($tmpId));
            }
        }else{
            $tmpFilesList = $this->files;
        }
        $tmpTags = array();
        foreach($tmpFilesList as $file){
            $tmpDescription = json_decode($file->getDescription(),true);
            if($tmpDescription != false){ //no json error
                $tmpTags = array_merge_recursive($tmpTags,$tmpDescription);
            }else{
                continue;
            }
        }
        $tags = array();
        foreach($tmpTags as $key=>$value){
            if(is_array($value)){
                //order number arrays non alphabetical
                if (is_numeric($value[0])) {
                  asort($value, SORT_NUMERIC);
                }
                $tags[$key] = array_values(array_unique($value));
            }else{
                $tags[$key] = $value;
            }
        }
        return $tags;
    }
    public function getDownloadLink($id){
        $file = $this->searchById($id);
        if($file){
          $link = $file->getExportLinks()['application/pdf'];
          //We on SlideWiki have pdf and gdoc files
          //exportLinks are for gdoc files, for pdf we just need a link
          if (!$link || strlen($link) < 9)
              if ($file->getFileExtension() == "pdf") {
                  $link = $file->webContentLink;

                  if (!$link || strlen($link) < 9)
                      $link = $file["alternateLink"];
              }
          return $link;
        }else{
            return false;
        }
    }
    public function getMetadataById($id){
        $file = $this->searchById($id);
        if($file){
            return $file->getDescription();
        }else{
            return false;
        }
    }
    public function getTitleById($id){
        $file = $this->searchById($id);
        if($file){
            return $file->getTitle();
        }else{
            return false;
        }
    }
    /**
     * @return Link to the Document in the Google GUI view
     * The intended function for the Webview API doesn't work with our documents
     * prob. because of the publishing strategy
     */
    public function getWebContentLink($id){
        $file = $this->searchById($id);
        if($file){
            $prefix = "https://docs.google.com/document/d/";
            return $prefix.$id;
        }else{
            return false;
        }
    }
    /**
     * Retrieve a list of File resources.
     *
     * @param Google_Service_Drive $service Drive API service instance.
     * @return Array List of Google_Service_Drive_DriveFile resources.
     */
    private function retrieveAllFiles($service) {
        $result = array();
        $pageToken = NULL;
        do {
            try {
                $parameters = array();
                if ($pageToken) {
                    $parameters['pageToken'] = $pageToken;
                }
                $files = $service->files->listFiles($parameters);
                $result = array_merge($result, $files->getItems());
                $pageToken = $files->getNextPageToken();
                } catch (Exception $e) {
                print "An error occurred: " . $e->getMessage();
                $pageToken = NULL;
            }
        } while ($pageToken);
        return $result;
    }
    /**
     * search for files with description rules
     * @param constraints: array of [key]:value pairs to be listed in the document description
     * e.g. "key=value;" or "type=thesis"
     * not using custom file properties because these can't be added via the googledoc GUI
     * @return array of IDs that fullfill the constraints
     */
    public function searchByMetadata($constraints){
        mylog('googleDriveHandler->searchByMetadata()');
        $result = array();
        mylog('We have '.count($this->files).' files here');
        foreach($this->files as $file){
            $tmpdescription = json_decode($file->getDescription(),true);
            if($tmpdescription == null){
                continue;
            }
            $constraintViolation = false;
            foreach($constraints as $key => $value){
              if((!isset($tmpdescription[$key]) || $tmpdescription[$key] != $value)&&
                 (!isset($tmpdescription[$key]) || !is_array($tmpdescription[$key])
                                                || !in_array($value,$tmpdescription  [$key]))){
                    $constraintViolation = true;
                    break;
                }
            }
            if(!$constraintViolation){
                array_push($result,$file->getId());
            }
            else {
              mylog('We have a violation here:');
              mylog($file);
            }
        }
        if(!empty($result)){
            return $result;
        }else{
            return NULL;
        }
    }
        /**
     * searches our List of documents for a specific title, returns file handle
     * @param $id: string; ID of the document to be searched for
     * @return file handle of a google document file object
     */
    public function searchById($id){
        foreach ($this->files as $file){
            //case sensitive
            if($file->getId() == $id){
                return $file;
            }
        }
        return false;
    }
}
