<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Http\Request;

[$router, $container] = require __DIR__ .'/../src/Core/bootstrap.php';

$request = $container->resolve(Request::class);

$router->dispatch($request, $container);