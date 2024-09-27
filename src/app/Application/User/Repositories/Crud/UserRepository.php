<?php

declare(strict_types = 1);

namespace App\Application\User\Repositories\Crud;

use App\Application\Database\DatabaseHandler;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        DatabaseHandler::getInstance()->connect();
    }

    public function registerUser($name, $email, $password): bool
    {
        return Capsule::table('users')->insert([
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
        ]);
    }
}
