<?php

declare(strict_types = 1);

namespace App\Application\User\Services\Crud;

interface UserServiceInterface
{
    public function registerUser($name, $email, $password, $nowDateTime): bool;
}
