<?php

use RingCentral\SDK\Http\HttpException;
use RingCentral\http\Response;
use RingCentral\SDK\SDK;


require('./vendor/autoload.php');

date_default_timezone_set ('UTC');


// To parse the .env file
$dotenv = new Dotenv\Dotenv(getcwd());

$dotenv->load();

require('./modules/_bootstrap.php');

$rcsdk = new SDK($_ENV['RC_AppKey'], $_ENV['RC_AppSecret'], $_ENV['RC_Server'], 'App', '1.0');
$platform = $rcsdk->platform();

require('./modules/init.php');
require('./modules/util.php');

rcLog($global_logFile, 1, 'Start to retrieve call logs from RingCentral PAS.');

require('./modules/auth.php');
require('./modules/extension.php');
require('./modules/calllog.php');
require('./modules/save_calllog.php');


$global_appData['lastRunningTime'] = $global_currentTime;
file_put_contents($global_appDataFile, json_encode($global_appData, JSON_PRETTY_PRINT));

