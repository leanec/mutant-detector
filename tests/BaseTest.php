<?php

declare(strict_types = 1);

namespace Tests;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

abstract class BaseTest extends TestCase
{
    public function runApp(string $requestMethod, string $requestUri, array $requestData = null): ResponseInterface 
    {
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );

        $request = Request::createFromEnvironment($environment);

        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        // Leyendo variables de .env
        $baseDir = __DIR__ . '/../';
        $dotenv = \Dotenv\Dotenv::createImmutable($baseDir);
        $dotenv->load();
        $dotenv->required(['DEVELOPMENT', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_PORT']);

        // Cargando configuracion
        $settings = require __DIR__ . '/../src/Settings.php';
        $app = new App($settings);

        // Cargando dependencias
        $container = $app->getContainer();
        require __DIR__ . '/../src/Dependencies.php';

        // Registrando rutas
        require __DIR__ . '/../src/Routes/Api.php';

        return $app->process($request, new Response());
    }
}