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

    public function getUser(int $userId): ?stdClass
    {
        return $this->userRepository->getUser($userId);
    }

    public function getUserRole(stdClass $user): ?stdClass
    {
        return $this->userRepository->getUserRole($user);
    }

    public function getUserByEmail(string $email): ?stdClass
    {
        return $this->userRepository->getUserByEmail($email);
    }

    public function getUserWithEmployeeCode(int $userId): ?stdClass
    {
        return $this->userRepository->getUserWithEmployeeCode($userId);
    }

    public function createUser(string $name, string $email, string $password, string $employeeCode, DateTime $nowDateTime, Role $roleName): int
    {
        return $this->userRepository->createUser($name, $email, $password, $employeeCode, $nowDateTime, $roleName);
    }

    public function updateUser(int $id, string $name, string $email, int $employeeCode, DateTime $nowDateTime): bool
    {
        return $this->userRepository->updateUser($id, $name, $email, $employeeCode, $nowDateTime);
    }

    public function deleteUser(int $id): int
    {
        return $this->userRepository->deleteUser($id);
    }

    /**
     * @return stdClass[]
     */
    public function listUsers(): array
    {
        return $this->userRepository->listUsers();
    }
}
