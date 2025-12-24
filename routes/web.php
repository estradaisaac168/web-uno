<?php

use App\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);


// $router->get('/courses/{id}', [HomeController::class, 'index'])
// ->middleware("");
