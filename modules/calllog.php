<?php

try {
    
    $maxRetrieveTimeSpan = $_ENV['RC_maxRetrieveTimespan'];

    $currentTime = time();
    $dateFromTime = $currentTime - $maxRetrieveTimeSpan;
    $dateToTime = $currentTime;
    
    if(isset($appData['lastRunningTime'])){
        if($currentTime - $appData['lastRunningTime'] <= $maxRetrieveTimeSpan) {
            $dateFromTime = $appData['lastRunningTime'] + 1;
        }
    }

    $callLogs = requestMultiPages($platform, '/account/~/call-log', array(
        'withRecording' => 'True',
        'dateFrom' => date('Y-m-d\TH:i:s\Z', $dateFromTime),
        'dateTo' => date('Y-m-d\TH:i:s\Z', $dateToTime),
        'type' => 'Voice',
        'perPage' => 500,
        'page' => 1
    ));
    
    $appData['lastRunningTime'] = $currentTime;
    
    file_put_contents($appDataFile, json_encode($appData, JSON_PRETTY_PRINT));
    
    if(count($callLogs) > 0) {
        rcLog($logFile, 0, 'Call Logs Loaded!');
        foreach ($callLogs as $callLog) {
            rcLog($logFile, 0, $callLog->uri);
        }
    }
    
} catch (Exception $e) {
    rcLog($logFile, 1, 'Error occurs when retrieving call logs -> ' . $e->getMessage());
    throw $e;    
}	



