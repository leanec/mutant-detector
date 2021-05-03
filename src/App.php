<?php

require __DIR__ . '/../vendor/autoload.php';

// Leyendo variables de .env
$baseDir = __DIR__ . '/../';
$dotenv = \Dotenv\Dotenv::createImmutable($baseDir);
$dotenv->load();
$dotenv->required(['DEVELOPMENT', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_PORT']);

// Cargando configuracion
$settings = require __DIR__ . '/Settings.php';
$app = new \Slim\App($settings);

// Cargando dependencias
$container = $app->getContainer();
require __DIR__ . '/Dependencies.php';

// Registrando rutas
require __DIR__ . '/Routes/Api.php';