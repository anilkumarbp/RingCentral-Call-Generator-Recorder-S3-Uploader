<?php

use Aws\S3\S3Client;
use Aws\Common\Aws;
use Aws\Ses\SesClient;

$client = S3Client::factory(array(
    'key' => $_ENV['amazonAccessKey'],
    'secret' => $_ENV['amazonSecretKey'],
    'region' => $_ENV['amazonRegion'],
    'command.params' => ['PathStyle' => true]
));
    
// Register the stream wrapper from an S3Client object
$client->registerStreamWrapper();

$s3FileName = "s3://".$_ENV['amazonS3Bucket'].'/'.$callLog['filePath'].'.'.$recording['ext'];

// Write the file to S3 Bucket
file_put_contents($s3FileName, $recording['data']);
