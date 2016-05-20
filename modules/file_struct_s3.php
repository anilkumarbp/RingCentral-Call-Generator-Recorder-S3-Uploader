<?php

$number = null;

if($callLog->direction == "Inbound") {
    if(property_exists($callLog->to, 'phoneNumber')){
        $number = $callLog->to->phoneNumber;
    }else{
        $number = $callLog->to->extensionNumber;
    }
}else{
    if(property_exists($callLog->from, 'phoneNumber')){
        $number = $callLog->from->phoneNumber;
    }else{
        $number = $callLog->from->extensionNumber;
    }
}

$extension = getExtension($number, $phoneNumbers, $accountExtensions);
if(!is_null($extension)){
    $filePath = ($extension->name).'/'.substr($callLog->startTime, 0, 10).'/'.
        substr($callLog->startTime, 11, 8)."_".$callLog->recording->id;
}else {
    $filePath = $number.'/'.substr($callLog->startTime, 0, 10).'/'.
        substr($callLog->startTime, 11, 8)."_".$callLog->recording->id;
}