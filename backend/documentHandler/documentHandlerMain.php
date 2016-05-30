<?php
require_once 'abstractDocumentHandler.php';

// read documentHandlerconfig

// load ../serviceConfigs/configLoader.php

// iterate over serviceConfigs determining which documentHandler to load

class documentHandlerMain extends abstractDocumentHandler{

    private $documentHandlerCollection;

    /**
     * instantiate the documentHandler 
     * @param configToken given by the configLoader.php
     */
    public function __construct($configToken){
        $this->documentHandlerCollection = array();
        foreach($configToken as $configEntry){
            array_push($this->documentHandlerCollection,$this->createInstance($configEntry));
        }
    }
    private function createInstance($configEntry) {
        $reflectionClass = new ReflectionClass($configEntry['cloudType']."handler");
        return $reflectionClass->newInstance($configEntry);
    }
    /**
     * function to retrieve all custom metadata from all Files in the cloud
     * @return decodeable json string (please see wiki page)
     */
    public function getAllMetadata(){
        $metadata = array();
        foreach($this->documentHandlerCollection as $documentHandler){
            $metadata = array_merge_recursive($metadata,$documentHandler->getAllMetadata());
        };
        return $metadata;
    }
    /**
     * function to get Download Link to be passed into frontend
     * @return string
     */
    public function getDownloadLink($id){
        foreach($this->documentHandlerCollection as $documentHandler){
            $tmp = $documentHandler->getDownloadLink($id);
            if($tmp){
                return $tmp;
            }
        }
        return false;    
    }
    /**
     * function to get all custom metadata for one File
     * @param id of the file
     * @return string json decodeable string (please see wiki page)
     */
    public function getMetadataById($id){
        foreach($this->documentHandlerCollection as $documentHandler){  
            $tmp = $documentHandler->getMetadataById($id);
            if($tmp){
                return $tmp;
            }
        }
        return false; 
    }
    /**
     * function to get the Title of a Document by Id
     * @param Id
     * @return string title of the document
     */
    public function getTitleById($id){
        foreach($this->documentHandlerCollection as $documentHandler){
            $tmp = $documentHandler->getTitleById($id);
            if($tmp){
                return $tmp;
            }
        }
        return false; 
    }
    /**
     * @param Id
     * @return Link to the Document in a webcontent view
     */
    public function getWebContentLink($id){
        foreach($this->documentHandlerCollection as $documentHandler){
            $tmp = $documentHandler->getWebContentLink($id);
            if($tmp){
                return $tmp;
            }
        }
        return false; 
    }
    /**
     * @return array of IDs that fullfill the constraints
     */
    public function searchByMetadata($constraints){
        $metadata = array();
        foreach($this->documentHandlerCollection as $documentHandler){
            $metadata = array_merge_recursive($metadata,$documentHandler->searchByMetadata($constraints));
        };
        return $metadata;
    }
}
