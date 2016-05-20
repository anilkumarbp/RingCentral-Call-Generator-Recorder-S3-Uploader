<?php

try {
    
    $maxRetrieveTimeSpan = $_ENV['RC_maxRetrieveTimespan'];

    $dateFromTime = $global_currentTime - $maxRetrieveTimeSpan;
    $dateToTime = $global_currentTime;
    
    if(isset($global_appData['lastRunningTime'])){
        if($global_currentTime - $global_appData['lastRunningTime'] <= $maxRetrieveTimeSpan) {
            $dateFromTime = $global_appData['lastRunningTime'] + 1;
        }
    }

    function getCallLogs($platform, $dateFromTime, $dateToTime) {
        try{
            return requestMultiPages($platform, '/account/~/call-log', array(
                'withRecording' => 'True',
                'dateFrom' => date('Y-m-d\TH:i:s\Z', $dateFromTime),
                'dateTo' => date('Y-m-d\TH:i:s\Z', $dateToTime),
                'type' => 'Voice',
                'perPage' => 1000
            ));
        }
        catch(Exception $e){
            $diff = floor(($dateToTime - $dateFromTime + 1) / 2);
            if($diff < 300) {
                throw $e;
            }else {
                return array_merge(getCallLogs($platform, $dateFromTime, $dateFromTime + $diff), 
                    getCallLogs($platform, $dateFromTime + $diff + 1, $dateToTime));       
            }
        }
    }

    $global_callLogs = getCallLogs($platform, $dateFromTime, $dateToTime);
    
    
    if(count($global_callLogs) > 0) {
        rcLog($global_logFile, 1, count($global_callLogs).' Call Logs Loaded!');
        foreach ($global_callLogs as $callLog) {
            rcLog($global_logFile, 0, $callLog->uri);
        }
    }
    
} catch (Exception $e) {
    rcLog($global_logFile, 2, 'Error occurs when retrieving call logs -> ' . $e->getMessage());
    throw $e;    
}	



