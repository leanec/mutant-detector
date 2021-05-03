<?php

require __DIR__ . '/../vendor/autoload.php';

$baseDir = __DIR__ . '/../';
$dotenv = Dotenv\Dotenv::createImmutable($baseDir);
$dotenv->load();
$dotenv->required(['DEVELOPMENT', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_PORT']);
$config = [
    'settings' => [
        'displayErrorDetails' => $_SERVER['DEVELOPMENT'],
        'db' => [
            'host' => $_SERVER['DB_HOST'],
            'name' => $_SERVER['DB_NAME'],
            'user' => $_SERVER['DB_USER'],
            'pass' => $_SERVER['DB_PASS'],
            'port' => $_SERVER['DB_PORT'],
        ],
    ],
];

$app = new \Slim\App($config);

require __DIR__ . '/Dependencias.php';
require __DIR__ . '/Routes/Api.php';