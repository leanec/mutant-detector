<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App();

require __DIR__ . '/Routes/Api.php';