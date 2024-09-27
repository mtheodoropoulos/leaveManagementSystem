<?php

declare(strict_types = 1);

namespace App\Application\User\Repositories\Crud;

interface UserRepositoryInterface
{
    public function registerUser($name, $email, $password): bool;

}
