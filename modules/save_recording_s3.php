<?php

use Aws\S3\S3Client;
use Aws\Common\Aws;
use Aws\Ses\SesClient;

if(count($filePaths) > 0) {
    $client = S3Client::factory(array(
        'key' => $_ENV['amazonAccessKey'],
        'secret' => $_ENV['amazonSecretKey'],
        'region' => $_ENV['amazonRegion'],
        'command.params' => ['PathStyle' => true]
    ));
        
    // Register the stream wrapper from an S3Client object
    $client->registerStreamWrapper();
    
    $s3FileName = "s3://".$_ENV['amazonS3Bucket'];
    foreach ($filePaths as $filePath) {
        $s3FileName = $s3FileName."/".$filePath;
    }
    
    // Write the file to S3 Bucket
    file_put_contents($s3FileName, $recording['data']);

    if(filesize($s3FileName) == 0) {
        rcLog($logFile, 1, 'Fail to send recording '.$recording['id'].' to S3 '.$s3FileName);
    }else {
        rcLog($logFile, 0, 'Successfully send recording '.$recording['id'].' to S3 '.$s3FileName);
    }
}