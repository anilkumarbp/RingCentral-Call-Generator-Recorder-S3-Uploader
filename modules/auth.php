<?php

try {

    // Retrieve previous authentication data
    $file = $cacheDir.'/platform.json';
    
    if (file_exists($file)) {
        $cachedAuth = json_decode(file_get_contents($file), true);
        $platform->auth()->setData($cachedAuth);
        
        if(!$platform->loggedIn()) {
            $platform->login($_ENV['RC_Username'], $_ENV['RC_Extension'], $_ENV['RC_Password']);
        }
    }else {
        $platform->login($_ENV['RC_Username'], $_ENV['RC_Extension'], $_ENV['RC_Password']);
    }
    file_put_contents($file, json_encode($platform->auth()->data(), JSON_PRETTY_PRINT));
    
    rcLog($logFile, 0, 'Authorization was restored');

} catch (Exception $e) {
    rcLog($logFile, 1, 'Error occurs when authorization: ' . $e->getMessage());
    throw $e;    
}	



