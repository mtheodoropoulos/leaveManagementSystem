<?php

declare(strict_types = 1);

namespace App\Application\Leave\Services\Crud;

interface LeaveServiceInterface
{
    public function listLeaves(int $userId): array;
}
