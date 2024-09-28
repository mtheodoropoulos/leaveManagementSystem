<?php

declare(strict_types = 1);

namespace App\Application\User\Repositories\Crud;

use App\Application\Database\DatabaseFactory;
use App\Application\Database\DatabaseHandler;
use Exception;
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
        $strategy = DatabaseFactory::create('mysql');
        DatabaseHandler::getInstance($strategy)->connect();
    }

    public function registerUser($name, $email, $password, $nowDateTime): bool
    {
        try {
            return Capsule::table('users')->insert([
                'name'       => $name,
                'email'      => $email,
                'password'   => $password,
                'created_at' => $nowDateTime,
                'updated_at' => $nowDateTime
            ]);
        } catch (Exception $e) {
            return false;
        }
    }
}
