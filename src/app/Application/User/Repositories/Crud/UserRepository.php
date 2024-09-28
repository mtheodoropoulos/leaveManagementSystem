<?php

declare(strict_types = 1);

namespace App\Application\User\Repositories\Crud;

use App\Application\User\Enums\Role;
use App\Application\Database\DatabaseFactory;
use App\Application\Database\DatabaseHandler;
use DateTime;
use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use stdClass;

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

    public function getUserRole($user): ?stdClass
    {
       return  Capsule::table('roles')
                        ->join('role_user', 'roles.id', '=', 'role_user.role_id')
                        ->where('role_user.user_id', $user->id)
                        ->select('roles.name')
                        ->first();
    }

    public function createUser(string $name, string $email, string $password, string $employeeCode, DateTime $nowDateTime, Role $roleName): bool
    {
        try {
            $roleId   = Capsule::table('roles')->where('name', $roleName->value)->value('id');
            $userId   = Capsule::table('users')->insertGetId([
                'name'       => $name,
                'email'      => $email,
                'password'   => $password,
                'created_at' => $nowDateTime,
                'updated_at' => $nowDateTime
            ]);


            Capsule::table('role_user')->insert([
                [
                    'role_id' => $roleId,
                    'user_id' => $userId,
                ],
            ]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return stdClass[]
     */
    public function listUsers(): array
    {
        return Capsule::table('users')
                            ->join('role_user', 'users.id', '=', 'role_user.user_id')
                            ->join('roles', 'role_user.role_id', '=', 'roles.id')
                            ->where('roles.name', '=', 'employee')
                            ->select('users.*')
                            ->get()->toArray();
    }
}
