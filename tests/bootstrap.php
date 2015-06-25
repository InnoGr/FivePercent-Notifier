<?php

$vendorDir = __DIR__ . '/../../..';

/** @var \Composer\Autoload\ClassLoader $loader */

if (file_exists($file = $vendorDir . '/autoload.php')) {
    $loader = require_once $file;
} else if (file_exists($file = './vendor/autoload.php')) {
    $loader = require_once $file;
} else {
    throw new \RuntimeException("Not found composer autoload");
}

$loader->addPsr4('FivePercent\\Component\\Notifier\\', __DIR__, true);
