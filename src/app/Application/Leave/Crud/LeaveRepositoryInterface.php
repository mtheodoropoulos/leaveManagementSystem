<?php

declare(strict_types = 1);

namespace App\Application\Leave\Crud;

interface LeaveRepositoryInterface
{
    public function listLeaves(int $userId): array;
}
