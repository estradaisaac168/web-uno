<?php

use App\Core\Container;
use App\Core\Router;
use App\Core\Http\Request;

$container = new Container();

$container->bind(Request::class, Request::class);

$router = new Router();

require __DIR__.'/../../routes/web.php';

return [$router, $container];
