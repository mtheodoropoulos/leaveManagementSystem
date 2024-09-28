<?php

declare(strict_types = 1);

namespace App\Application\User\Services\Crud;

use App\Application\User\Enums\Role;
use App\Application\User\Repositories\Crud\UserRepository;
use DateTime;
use stdClass;

class UserService implements UserServiceInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function getUserRole(stdClass $user): ?stdClass
    {
        return $this->userRepository->getUserRole($user);
    }

    public function registerUser(string $name, string $email, string $password, string $employeeCode, DateTime $nowDateTime, Role $roleName): bool
    {
        return $this->userRepository->createUser($name, $email, $password, $employeeCode, $nowDateTime, $roleName);
    }

    /**
     * @return stdClass[]
     */
    public function listUsers(): array
    {
        return $this->userRepository->listUsers();
    }
}
