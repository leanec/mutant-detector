<?php

declare(strict_types = 1);

return [
    'settings' => [
        'displayErrorDetails' => $_SERVER['DEVELOPMENT'],
        'db' => [
            'host' => $_SERVER['DB_HOST'],
            'name' => $_SERVER['DB_NAME'],
            'user' => $_SERVER['DB_USER'],
            'pass' => $_SERVER['DB_PASS'],
            'port' => $_SERVER['DB_PORT'],
        ],
        'redis' => [
            'enabled' => $_SERVER['REDIS_ENABLED'],
            'url' => $_SERVER['REDIS_URL'],
        ],
    ],
];