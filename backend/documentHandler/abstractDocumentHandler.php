<?php
/**
 * abstract class for providing methods on the documents of a virtual shared drive
 *
 *
 */
abstract class abstractDocumentHandler
{

    private $files;

    /**
     * instantiate the documentHandler 
     * @param configToken given by the configLoader.php
     */
    abstract public function __construct($configToken);
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
