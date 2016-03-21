<?php
    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    define('INC_ROOT', dirname(__DIR__));

    require INC_ROOT . '/vendor/autoload.php';

    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();

    $dotenv = new Dotenv\Dotenv(INC_ROOT);
    $dotenv->load();