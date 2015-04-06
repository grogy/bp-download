<?php

include_once __DIR__ . '/../bootstrap.php';

/**
 * Insert data from XMLs to database
 */

$import = $container->getService('databaseImport');
foreach (\Nette\Utils\Finder::findFiles('cswiki-page-*')->from(TEMP_DIR . '/') as $file) {
    $import->importPages($file);
}
