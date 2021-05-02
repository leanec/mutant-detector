<?php

$app->post('/mutant', 'App\Controllers\Mutant:isMutant')->add(new App\Middleware\Validator());