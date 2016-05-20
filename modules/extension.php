<?php

try {

    $global_accountExtensions = requestMultiPages($platform, '/account/~/extension', array(
        'perPage' => 1000
    ));
    

    rcLog($global_logFile, 1, count($global_accountExtensions)." Extensions Loaded!");
    foreach ($global_accountExtensions as $extension) {
        if(property_exists($extension, 'extensionNumber')) {
            rcLog($global_logFile, 0, $extension->extensionNumber . ":" . $extension->name);
        }
    }
    
    $global_phoneNumbers = requestMultiPages($platform, '/account/~/phone-number', array(
        'usageType' => 'DirectNumber',
        'perPage' => 1000
    ));
    
    rcLog($global_logFile, 1, count($global_phoneNumbers)." Phone Numbers Loaded!");
    foreach ($global_phoneNumbers as $number) {
        if(property_exists($number, 'extension') && property_exists($number->extension, 'extensionNumber')){
            rcLog($global_logFile, 0, $number->extension->extensionNumber . ':' . $number->phoneNumber);
        }
        else{
            rcLog($global_logFile, 0, $number->phoneNumber);
        }
    }
    
} catch (Exception $e) {
    rcLog($global_logFile, 2, 'Error occurs when retrieving extension and phone numbers -> ' . $e->getMessage());
    throw $e;    
}	



