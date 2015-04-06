<?php

include_once __DIR__ . '/../bootstrap.php';

/**
 * It create bash script with commands for download czech version of Wikipedia
 */

$listOfLinks = $container->getService('listOfLinks');
$pathToNewBashScript = TEMP_DIR . '/files-for-download.sh';
$listOfLinks->generateDownloadFile($pathToNewBashScript);
