<?php

declare(strict_types = 1);

namespace App\Application\Router;

use App\Application\http\Controllers\AuthController;
use App\Application\http\Controllers\UserController;

class Routes
{
    public static function getRoutes(): array
    {
        return [
            [
                'method'     => 'get',
                'path'       => '/',
                'controller' => AuthController::class,
                'action'     => 'showRegister'
            ],
            [
                'method'     => 'get',
                'path'       => '/register',
                'controller' => AuthController::class,
                'action'     => 'showRegister'
            ],
            [
                'method'     => 'post',
                'path'       => '/register',
                'controller' => AuthController::class,
                'action'     => 'postRegister'
            ],
            [
                'method'     => 'get',
                'path'       => '/login',
                'controller' => AuthController::class,
                'action'     => 'showLogin'
            ],
            [
                'method'     => 'post',
                'path'       => '/login',
                'controller' => AuthController::class,
                'action'     => 'postLogin'
            ],
            [
                'method'     => 'get',
                'path'       => '/listUsers',
                'controller' => UserController::class,
                'action'     => 'listUsers'
            ],
        ];
    }
}
