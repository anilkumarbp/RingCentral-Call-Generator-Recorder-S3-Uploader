<?php

try {

    $accountExtensions = requestMultiPages($platform, '/account/~/extension', array(
        'perPage' => 500,
        'page' => 1
    ));
    

    rcLog($logFile, 0, "Extension Loaded!");
    foreach ($accountExtensions as $extension) {
        rcLog($logFile, 0, $extension->extensionNumber . ":" . $extension->name);
    }
    
    $phoneNumbers = requestMultiPages($platform, '/account/~/phone-number', array(
        'usageType' => 'DirectNumber',
        'perPage' => 500,
        'page' => 1
    ));
    
    rcLog($logFile, 0, "Phone Numbers Loaded!");
    foreach ($phoneNumbers as $number) {
        rcLog($logFile, 0, $number->phoneNumber);
    }
    
} catch (Exception $e) {
    rcLog($logFile, 1, 'Error occurs when retrieving extension and phone numbers -> ' . $e->getMessage());
    throw $e;    
}	



