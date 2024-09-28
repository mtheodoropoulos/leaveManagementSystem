<?php

declare(strict_types = 1);

namespace App\Application\User\Services\Crud;

use App\Application\User\Repositories\Crud\UserRepository;
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

    public function registerUser($name, $email, $password, $nowDateTime): bool
    {
        return $this->userRepository->registerUser($name, $email, $password, $nowDateTime);
    }

    /**
     * @return stdClass[]
     */
    public function listUsers(): array
    {
        return $this->userRepository->listUsers();
    }
}
