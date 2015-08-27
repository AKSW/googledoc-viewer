<?php

/**
 * class for providing methods on the documents of a google drive
 * 
 *
 */

class DocumentHandler{

    private $credentials;
    private $client;
    private $service;
    private $files;

    /**
     * instantiate every object with a working google api service
     *
     */
    public function __construct($client_email, //service account email adress
    $scopes, //scopes
    $private_key, //P12 key downloaded from project credentials
    $privatekey_pass,
    $grant, // grant type
    $user_to_impersonate)// email adress
    {
        $this->credentials = new Google_Auth_AssertionCredentials(
        $client_email, //service account email adress
        $scopes,
        $private_key, //P12 key downloaded from project credentials
        $privatekey_pass,
        'http://oauth.net/grant_type/jwt/1.0/bearer', // Default grant type
        $user_to_impersonate // email adress
        );
        $this->client = new Google_Client();
        $this->client->setAssertionCredentials($this->credentials);
        if ($this->client->getAuth()->isAccessTokenExpired()) {
            $this->client->getAuth()->refreshTokenWithAssertion();
        }
        $this->service = new Google_Service_Drive($this->client);
        $this->files = $this->retrieveAllFiles($this->service);
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
     * printing all titles from the retrieved files
     *
     */
    public function printtitles(){
        foreach ($this->files as $file){
            print($file->getTitle()."\n");
        }
    }
    /**
     * get all document IDs 
     * @return array of google document IDs
     */
    public function getIDs(){
        $result = array();
        foreach ($this->files as $file){
            array_push($result,$file->getId());
        }
        return $result;
    }
    
    /**
     * searches our List of documents for a specific title, returns ID
     * @param $title: string; title of the document to be searched for (case-sensitive)
     * @return string: document ID
     */    
    public function searchByTitle($title){
        foreach ($this->files as $file){
            if($file->getTitle() == $title){//case sensitive
                return $file->getId();
            }
        }
        return false;
    }
    /**
     * searches our List of documents for a specific title, returns file handle
     * @param $id: string; ID of the document to be searched for
     * @return file handle of a google document file object
     */    
    public function searchById($id){
        foreach ($this->files as $file){
            $tmpid = $file->getId();
            if($tmpid == $id){//case sensitive
                return $file;
            }
        }
        return false;
    }
    /**
     * search for files with description rules
     * @param constraints: array of [key]:value pairs to be listed in the document description
     * e.g. "key=value;" or "type=thesis"
     * not using custom file properties because these can't be added via the googledoc GUI
     * @return array of IDs that fullfill the constraints
     */
    public function searchByDescription($constraints,$sublist = NULL){
        $files = array();
        $result = array();
        if($sublist){
            foreach($sublist as $element){
                array_push($files,$this->searchById($element));
            }
        }else{
            $files = $this->files;
        }
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
        }   
        if(!empty($result)){
            return $result;
        }else{
            return NULL;
        }    
    }
    /**
     * searches all files for supervisor-description tags and collects entries
     * @return: array of supervisors
     */
    public function getTags(){
        $tmpTags = array();
        foreach($this->files as $file){
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
                $tags[$key] = array_values(array_unique($value));
            }else{
                $tags[$key] = $value;
            }
        }
        return $tags;
    }   
    public function getTitleById($id){
        return $this->searchById($id)->getTitle();
    }
    public function getDescription($file){
        return $file->getDescription();
    }
    public function getDownloadLink($file){
        return $file->getExportLinks()['application/pdf'];
    }
    public function deleteFile($fileId) {
        try {
            $this->service->files->delete($fileId);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return false;
    }
}
