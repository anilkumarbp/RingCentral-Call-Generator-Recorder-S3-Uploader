<?php

$number = null;

if($callLog->direction == "Inbound") {
    $number = $callLog->to->phoneNumber;
}else{
    $number = $callLog->from->phoneNumber;
}

$extension = getExtension($number, $phoneNumbers, $accountExtensions);
if(!is_null($extension)){
    array_push($filePaths, $extension->name, substr($callLog->startTime, 0, 10), 
        substr($callLog->startTime, 11, 8)."_".$callLog->recording->id.".".$recording['ext']);
}else {
    array_push($filePaths, $number, substr($callLog->startTime, 0, 10), 
        substr($callLog->startTime, 11, 8)."_".$callLog->recording->id.".".$recording['ext']);
}