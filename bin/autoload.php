<?php

$locations = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../autoload.php'
];

foreach ($locations as $file) {
    if (file_exists($file)) {
        /** @noinspection PhpIncludeInspection */
        return require_once $file;
    }
}

echo 'Could not resolve path to Composer autoloader.' . PHP_EOL;
exit(1);
