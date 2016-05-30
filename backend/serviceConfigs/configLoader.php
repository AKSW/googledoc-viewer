<?php
$configToken = array();
//read this directory (serviceConfigs)
$directoryList = scandir(__DIR__);
foreach($directoryList as $entry){
    if(strpos($entry,".ini") == (strlen($entry) - 4)){
        $ini = parse_ini_file($entry);
        $ini_keys = array_keys($ini);
        for($i = 0; $i < sizeof($ini_keys); $i++){
            $ini[$ini_keys[$i]] = str_replace("DIR",__DIR__,$ini[$ini_keys[$i]]);
        }
        array_push($configToken,$ini);
    }
}



//filter service config files

// load all configs organized in this folder

//parse_ini_file

// gather information
