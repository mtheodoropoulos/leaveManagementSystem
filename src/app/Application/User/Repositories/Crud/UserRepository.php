<?php

declare(strict_types = 1);

namespace App\Application\User\Repositories\Crud;

use App\Application\User\Enums\Role;
use DateTime;
use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;
use stdClass;

class UserRepository implements UserRepositoryInterface
{
    public function __construct()
    {
    }

    public function getUser(int $userId): ?stdClass
    {
        return Capsule::table('users')->where('id', $userId)->first();
    }

    public function getUserRole($user): ?stdClass
    {
        return Capsule::table('roles')
                      ->join('role_user', 'roles.id', '=', 'role_user.role_id')
                      ->where('role_user.user_id', $user->id)
                      ->select('roles.name')->first();
    }

    public function createUser(string $name, string $email, string $password, string $employeeCode, DateTime $nowDateTime, Role $roleName): int
    {
        try {
            $roleId = Capsule::table('roles')->where('name', $roleName->value)->value('id');
            $userId = Capsule::table('users')->insertGetId([
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

            Capsule::table('employees')->insert([
                'userId'       => $userId,
                'employeeCode' => $employeeCode,
            ]);

            return $userId;
        } catch (Exception $e) {
            return 0;
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
                      ->join('employees', 'users.id', '=', 'employees.userId')
                      ->where('roles.name', '=', 'employee')
                      ->select('users.*', 'employees.employeeCode')
                      ->get()
                      ->toArray();
    }
}
