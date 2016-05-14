<?php

$cacheDir = './_cache';
$appDataFile = $cacheDir . '/app_data.json';
$logFile = $cacheDir.'/log';

if (!file_exists($cacheDir)) {
    mkdir($cacheDir);
}

$appData = array(
    'lastRunningTime' => null
);

if (file_exists($appDataFile)) {
    $appData = json_decode(file_get_contents($appDataFile), true);
}

function requestMultiPages($platform, $url, $options) {
    
    $results = array();
    
    $pageCount = 1;
    $flag = true;
    
    while($flag) {

        $apiResponse = $platform->get($url, $options);
        $apiResponseJSONArray = $apiResponse->json();
        $records = $apiResponseJSONArray->records;
        
        foreach ($records as $record) {
            array_push($results, $record);
        } 
        
        if(property_exists($apiResponseJSONArray->paging, 'totalPages')) {
            $totalPages = $apiResponseJSONArray->paging->totalPages;
            $page = $apiResponseJSONArray->paging->page;
            if($page <= $totalPages) {
                $pageCount = $pageCount + 1;
                if($page == $totalPages) {
                    $flag = false;
                }
            }
        }else {
            if(isset($apiResponseJSONArray->navigation->nextPage)){
                $pageCount = $pageCount + 1;
            }else{
                $flag = false;
            }
        }
    }
    
    return $results;
}

function retrieveRecording($platform, $callLog) {
    $uri = $callLog->recording->contentUri;
    $apiResponse = $platform->get($uri);
    $ext = ($apiResponse->response()->getHeader('Content-Type')[0] == 'audio/mpeg')
        ? 'mp3' : 'wav';
    return array(
        'id' => $callLog->recording->id,
        'ext' => $ext,
        'data' => $apiResponse->raw()
    );    
}

function getExtension($number, $phoneNumbers, $extensions) {
    foreach ($phoneNumbers as $phoneNumber) {
        if($number == $phoneNumber->phoneNumber) {
            foreach ($extensions as $ext) {
                if(isset($phoneNumber->extension)){
                    print($ext->extensionNumber);
                    if($ext->extensionNumber == $phoneNumber->extension->extensionNumber) {
                        return $ext;
                    }    
                }
            }
        }
    }
    
    return null;
}

function rcLog($logFile, $level, $message) {
    if($level >= $_ENV['Log_Level']) {
        $currentTime = date('Y-m-d H:i:s', time());
        $info = $level==0?"Info":"Error";
        file_put_contents($logFile, $currentTime."[".$info."]"." -> ".$message.PHP_EOL, FILE_APPEND);
    }
}