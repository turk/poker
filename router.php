<?php

use App\Controllers\AuthController;
use App\Controllers\GameController;
use App\Controllers\IndexController;
use App\Controllers\PlayController;
use App\Controllers\UploadController;

return $routes = [
    ['httpMethod' => 'GET', 'uri' => '/', 'class' => IndexController::class, 'method' => 'index', 'middleware' => 'auth' ],
    ['httpMethod' => 'GET', 'uri' => '/login', 'class' => AuthController::class, 'method' => 'getLogin' ],
    ['httpMethod' => 'POST', 'uri' => '/login', 'class' => AuthController::class, 'method' => 'postLogin' ],
    ['httpMethod' => 'GET', 'uri' => '/logout', 'class' => AuthController::class, 'method' => 'logout' ],
    ['httpMethod' => 'POST', 'uri' => '/upload', 'class' => UploadController::class, 'method' => 'index' ],
    ['httpMethod' => 'GET', 'uri' => '/play', 'class' => PlayController::class, 'method' => 'index' ],
    ['httpMethod' => 'GET', 'uri' => '/game/delete-all', 'class' => GameController::class, 'method' => 'deleteAllGame' ],
];
