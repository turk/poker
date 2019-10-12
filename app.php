<?php

use App\Bootstrap;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Middleware;

$capsule = new Capsule;
$middleware = new Middleware();

$bootstrap = new Bootstrap($capsule, $middleware);
$bootstrap->connection();
$bootstrap->router($routes);

