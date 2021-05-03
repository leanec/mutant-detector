<?php

$container = $app->getContainer();

$container['db'] = static function ($container) {
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

$container['record_repository'] = static fn ($container) => new App\Repositories\RecordRepository (
    $container->get('db')
);

$container['record_service'] = static fn ($container) => new App\Services\RecordService (
    $container->get('record_repository')
);