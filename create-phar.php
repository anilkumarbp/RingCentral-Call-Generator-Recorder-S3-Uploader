<?php

exec('rm -rf ' . __DIR__ . '/build');
@mkdir('./build');
$srcRoot = "~/RingCentral-Call-Generator-Recordings-Downloader/";
$buildRoot = "./build";
 
$phar = new Phar($buildRoot . "/app.phar", 
    FilesystemIterator::CURRENT_AS_FILEINFO |  FilesystemIterator::KEY_AS_FILENAME, "app.phar");

function listDir($root, $path, $phar)
{
    //print 'Entering ' . $root . $path . PHP_EOL;
    $it = new DirectoryIterator($root . $path);
    foreach ($it as $fileinfo) {
        $filename = $fileinfo->getFilename();
        if ($fileinfo->isDot() ||
            stristr($filename, 'Test.php') ||
            stristr($filename, '.git') ||
            stristr($filename, '.gitignore') ||
            stristr($filename, 'manual_tests') ||
            stristr($filename, 'tests') ||
            stristr($filename, 'demo') ||
            stristr($filename, 'Call-Logs') ||
            stristr($filename, 'Json') ||
            stristr($filename, 'Csv') ||
            stristr($filename, 'Recordings') ||
            stristr($filename, '_cache') ||
            stristr($filename, 'dist') ||
            stristr($filename, 'build') ||
            stristr($filename, 'docs')
        ) {
            continue;
        } elseif ($fileinfo->isDir()) {
            listDir($root, $path . '/' . $filename, $phar);
        } else {
            $key = ($path ? $path . '/' : '') . $filename;
            $phar[$key] = file_get_contents($root . $path . '/' . $fileinfo->getFilename());
            //print '  ' . $key . ' -> ' . $path . '/' . $filename . PHP_EOL;
        }
    }
}

listDir(__DIR__ . '/', '', $phar);

$phar->setStub($phar->createDefaultStub("run.php"));
