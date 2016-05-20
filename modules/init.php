<?php

$global_cacheDir = './_cache';
$global_appDataFile = $global_cacheDir . '/app_data.json';
$global_logFile = $global_cacheDir.'/log';
$global_currentTime = time();

$global_accountExtensions = null;
$global_phoneNumbers = null;
$global_callLogs = null;

if (!file_exists($global_cacheDir)) {
    mkdir($global_cacheDir);
}

$global_appData = array(
    'lastRunningTime' => null
);

if (file_exists($global_appDataFile)) {
    $global_appData = json_decode(file_get_contents($global_appDataFile), true);
}







