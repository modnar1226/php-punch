<?php
// Composer autoload
require_once dirname(__DIR__) . '/vendor/autoload.php';
// Custom autoloader for php-punch
$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4("Punch\\", __DIR__, true);
$classLoader->register();

define('PLAYBOOK', 'my.example');
