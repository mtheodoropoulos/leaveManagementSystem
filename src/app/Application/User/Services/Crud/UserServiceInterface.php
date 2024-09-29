<?php

declare(strict_types = 1);

namespace App\Application\User\Services\Crud;

use App\Application\User\Enums\Role;
use DateTime;

interface UserServiceInterface
{
    public function createUser(string $name, string $email, string $password, string $employeeCode, DateTime $nowDateTime, Role $roleName): int;
}
