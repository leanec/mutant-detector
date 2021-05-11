<?php

declare(strict_types = 1);

use Psr\Container\ContainerInterface;

// Database
$container['db'] = static function (ContainerInterface $container) : PDO 
{
    $database = $container->get('settings')['db'];
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;port=%s;charset=utf8',
        $database['host'],
        $database['name'],
        $database['port']
    );
    $pdo = new PDO($dsn, $database['user'], $database['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    return $pdo;
};

// Redis
$container['redis_service'] = static function (ContainerInterface $container): ?App\Services\RedisService 
{
    $redis = $container->get('settings')['redis'];
    
    if (filter_var($redis['enabled'], FILTER_VALIDATE_BOOLEAN)) {
        $client = new Redis();
        $client->connect($redis['host'], intval($redis['port']));
        return new App\Services\RedisService($client);
    }
    
    return null;
};

// Repositories and services
$container['record_repository'] = static fn (ContainerInterface $container) => new App\Repositories\RecordRepository (
    $container->get('db')
);

$container['record_service'] = static fn (ContainerInterface $container) => new App\Services\RecordService (
    $container->get('record_repository'),
    $container->get('redis_service')
);

// Error handlers
$container['errorHandler'] = function (ContainerInterface $container) : \Slim\Handlers\Error
{
    return new App\Handlers\ApiError();
};

$container['notFoundHandler'] = function (ContainerInterface $container) : \Slim\Handlers\NotFound
{
    return new App\Handlers\NotFound();
};