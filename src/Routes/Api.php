<?php

$app->post('/mutant', 'App\Controllers\RecordController:isMutant')->add(new App\Middleware\Validator());
$app->get('/stats', 'App\Controllers\RecordController:stats');