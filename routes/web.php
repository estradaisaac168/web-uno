<?php

use App\Controllers\HomeController;

$router->get('/courses', [HomeController::class, 'index']);


// $router->get('/courses/{id}', [HomeController::class, 'index'])
// ->middleware("");
