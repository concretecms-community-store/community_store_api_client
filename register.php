<?php

// Use this file if you are not using Composer (https://getcomposer.org)

spl_autoload_register(static function (string $class): void {
    $classPrefix = 'CommunityStore\\APIClient\\';
    if (strpos($class, $classPrefix) !== 0) {
        return;
    }
    $filePrefix = __DIR__ . '/src/';
    $file = $filePrefix . str_replace('\\', '/', substr($class, strlen($classPrefix))) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
