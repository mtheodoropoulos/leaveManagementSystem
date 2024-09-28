<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

class DatabaseSeeder
{
    public function run(): void
    {
        $this->seedRolesPermissions();

        $this->seedUsers();
    }

    private function seedRolesPermissions(): void
    {
        $roles = [
            'manager' => [
                ['name' => 'create_user'],
                ['name' => 'update_user'],
                ['name' => 'view_user'],
                ['name' => 'delete_user'],
            ],
            'employee' => [
                ['name' => 'create_vacation'],
                ['name' => 'view_vacation'],
                ['name' => 'update_vacation'],
                ['name' => 'delete_vacation'],
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $roleId = Capsule::table('roles')->insertGetId(['name' => $roleName]);

            foreach ($permissions as $permission) {
                $permissionId = Capsule::table('permissions')->insertGetId($permission);

                Capsule::table('role_permission')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        echo "Roles and permissions seeded successfully.\n";
    }

    private function seedUsers(): void
    {
        $users = [
            [
                'name' => 'Test user Manager',
                'email' => 'manager@mail.com',
                'password' => password_hash('password1234', PASSWORD_DEFAULT),
                'employeeCode' => 1001,
                'role' => 'manager',
            ],
            [
                'name' => 'Test user Employee',
                'email' => 'employee1@mail.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'employeeCode' => 1002,
                'role' => 'employee',
            ],
        ];

        foreach ($users as $user) {
            $userId = Capsule::table('users')->insertGetId([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if ($user['role'] === 'manager') {
                Capsule::table('managers')->insert([
                    'userId' => $userId,
                ]);
            } elseif ($user['role'] === 'employee') {
                Capsule::table('employees')->insert([
                    'userId' => $userId,
                    'employeeCode' => $user['employeeCode'],
                ]);
            }

            $this->assignRoleToUser($userId, $user['role']);
        }

        echo "Users seeded successfully.\n";
    }

    private function assignRoleToUser($userId, $roleName): void
    {
        $roleId = Capsule::table('roles')->where('name', $roleName)->value('id');
        Capsule::table('role_user')->insert([
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);

        echo "Assigned role '{$roleName}' to user with ID {$userId}.\n";
    }
}

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
//    'host' => '127.0.0.1', // Adjust as needed
    'host'      => 'laravel-mysql', // Adjust as needed
    'database'  => 'db_main',
    'username'  => 'mixalis',
    'password'  => 'theodoropo',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
$seeder = new DatabaseSeeder();
$seeder->run();
