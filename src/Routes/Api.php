<?php

declare(strict_types = 1);

use App\Middleware\Validator;

$app->post('/mutant', 'App\Controllers\RecordController:isMutant')->add(new Validator());
$app->get('/stats', 'App\Controllers\RecordController:stats');