<?php
class ConfigLoader{
    public $configToken;
    function __construct(){
        $this->configToken =  array();
        //read current directory (serviceConfigs)
        $directoryList = scandir(__DIR__);
        foreach($directoryList as $entry){
            //filter current directory for every .ini file excluding .ini.tpl files
            if(strpos($entry,".ini") == (strlen($entry) - 4)){
                $ini = parse_ini_file($entry);
                $ini_keys = array_keys($ini);
                //iterate over every ini entry
                for($i = 0; $i < sizeof($ini_keys); $i++){
                    //replace DIR with actual current directory
                    //for reading with file_get_contents of binary files lateron
                    $ini[$ini_keys[$i]] = str_replace("DIR",__DIR__,$ini[$ini_keys[$i]]);
                }
                array_push($this->configToken,$ini);
            }
        }
    }
}
