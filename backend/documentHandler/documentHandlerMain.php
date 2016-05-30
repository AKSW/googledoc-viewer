<?php
require_once 'abstractDocumentHandler.php';
// initialize all document Handler in this folder, give interface to outside, merging all documents together

// read documentHandlerconfig

// load ../serviceConfigs/configLoader.php

// iterate over serviceConfigs determining which documentHandler to load

class documentHandlerMain extends abstractDocumentHandler{

    private $documentHandler;

    /**
     * instantiate the documentHandler 
     * @param configToken given by the configLoader.php
     */
    public function __construct($configToken){
        foreach($configtoken as $configEntry){
            $type = $configEntry['cloudType'];
        }
    
    }
    /**
     * function to retrieve all custom metadata from all Files in the cloud
     * @return decodeable json string (please see wiki page)
     */
    abstract public function getAllMetadata();
    /**
     * function to get Download Link to be passed into frontend
     * @return string
     */
    abstract public function getDownloadLink($id);
    /**
     * function to get all custom metadata for one File
     * @param id of the file
     * @return string json decodeable string (please see wiki page)
     */
    abstract public function getMetadataById($id);
    /**
     * function to get the Title of a Document by Id
     * @param Id
     * @return string title of the document
     */
    abstract public function getTitleById($id);
    /**
     * @param Id
     * @return Link to the Document in a webcontent view
     */
    abstract public function getWebContentLink($id);
    /**
     * @return array of IDs that fullfill the constraints
     */
    abstract public function searchByMetadata($constraints);
    /**
     * @param Id
     * @return file handle of a  document file object
     */
    abstract public function searchById($id);
}
