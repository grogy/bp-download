<?php

include_once __DIR__ . '/../bootstrap.php';

/**
 * Download article from english version Wikipedia
 */

$listOfLinks = $container->getService('englishVersionModel');
$listOfLinks->downloadNextPageForPortal('Film');
