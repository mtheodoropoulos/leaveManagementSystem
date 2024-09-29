<?php

declare(strict_types = 1);

namespace App\Application\Router;

use App\Application\http\Controllers\AuthController;
use App\Application\http\Controllers\LeaveController;
use App\Application\http\Controllers\UserController;
use App\Application\Middleware\VerifyCsrfMiddleware;
use App\Application\Middleware\VerifySessionMiddleware;

class Routes
{
    public static function getRoutes(): array
    {
        return [
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
                'action'     => 'postRegister',
                'middleware' => [VerifyCsrfMiddleware::class]
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
                'action'     => 'postLogin',
                'middleware' => [VerifyCsrfMiddleware::class]
            ],
            [
                'method'     => 'post',
                'path'       => '/logout',
                'controller' => AuthController::class,
                'action'     => 'logout',
                'middleware' => [VerifySessionMiddleware::class, VerifyCsrfMiddleware::class]
            ],
            [
                'method'     => 'get',
                'path'       => '/listUsers',
                'controller' => UserController::class,
                'action'     => 'listUsers',
                'middleware' => [VerifySessionMiddleware::class]
            ],
            [
                'method'     => 'get',
                'path'       => '/showCreateUser',
                'controller' => UserController::class,
                'action'     => 'showCreateUser',
                'middleware' => [VerifySessionMiddleware::class]
            ],
            [
                'method'     => 'post',
                'path'       => '/createUser',
                'controller' => UserController::class,
                'action'     => 'createUser',
                'middleware' => [VerifySessionMiddleware::class, VerifyCsrfMiddleware::class]
            ],
            [
                'method'     => 'get',
                'path'       => '/editUser/{id}',
                'controller' => UserController::class,
                'action'     => 'editUser',
                'middleware' => [VerifySessionMiddleware::class]
            ],
            [
                'method'     => 'post',
                'path'       => '/updateUser/{id}',
                'controller' => UserController::class,
                'action'     => 'updateUser',
                'middleware' => [VerifySessionMiddleware::class, VerifyCsrfMiddleware::class]
            ],
            [
                'method'     => 'delete',
                'path'       => '/deleteUser/{id}',
                'controller' => UserController::class,
                'action'     => 'deleteUser',
                'middleware' => [VerifySessionMiddleware::class, VerifyCsrfMiddleware::class]
            ],
            [
                'method'     => 'get',
                'path'       => '/listLeaves',
                'controller' => LeaveController::class,
                'action'     => 'listLeaves',
                'middleware' => [VerifySessionMiddleware::class]
            ],
            [
                'method'     => 'get',
                'path'       => '/showCreateLeave',
                'controller' => LeaveController::class,
                'action'     => 'showCreateLeave',
                'middleware' => [VerifySessionMiddleware::class]
            ],
            [
                'method'     => 'post',
                'path'       => '/createLeave',
                'controller' => LeaveController::class,
                'action'     => 'createLeave',
                'middleware' => [VerifySessionMiddleware::class, VerifyCsrfMiddleware::class]
            ],
            [
                'method'     => 'get',
                'path'       => '/editLeave/{id}',
                'controller' => LeaveController::class,
                'action'     => 'editLeave',
                'middleware' => [VerifySessionMiddleware::class]
            ],
            [
                'method'     => 'post',
                'path'       => '/updateLeave/{id}',
                'controller' => LeaveController::class,
                'action'     => 'updateLeave',
                'middleware' => [VerifySessionMiddleware::class, VerifyCsrfMiddleware::class]
            ],
            [
                'method'     => 'delete',
                'path'       => '/deleteLeave/{id}',
                'controller' => LeaveController::class,
                'action'     => 'deleteLeave',
                'middleware' => [VerifySessionMiddleware::class, VerifyCsrfMiddleware::class]
            ]
        ];
    }
}
