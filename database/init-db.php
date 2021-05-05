<?php

declare(strict_types = 1);

require __DIR__ . '/../src/App.php';

try {
    $db = $container->get('settings')['db'];
    $host = $db['host'];
    $name = $db['name'];
    $user = $db['user'];
    $pass = $db['pass'];
    $port = $db['port'];

    $pdo = new PDO("mysql:host=${host};port=$port;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("DROP DATABASE IF EXISTS ${name}");
    $pdo->exec("CREATE DATABASE ${name}");
    $pdo->exec("USE ${name}");
    $sql = file_get_contents(__DIR__ . '/database.sql');
    $pdo->exec($sql);
} catch (PDOException $exception) {
    echo $exception->getMessage();
}