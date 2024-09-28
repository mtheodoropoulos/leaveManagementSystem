<?php

declare(strict_types = 1);

namespace App\Application\User\Repositories\Crud;

use App\Application\User\Enums\Role;
use DateTime;

interface UserRepositoryInterface
{
    public function createUser(string $name, string $email, string $password, string $employeeCode, DateTime $nowDateTime, Role $roleName): bool;
}
