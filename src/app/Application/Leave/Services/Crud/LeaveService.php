<?php

declare(strict_types = 1);

namespace App\Application\Leave\Services\Crud;

use App\Application\Exceptions\UserNotEmployeeException;
use App\Application\Leave\Crud\LeaveRepository;
use DateTime;
use stdClass;

readonly class LeaveService implements LeaveServiceInterface
{
    public function __construct(private LeaveRepository $leaveRepository)
    {
    }

    public function listLeaves(int $userId): array
    {
        return $this->leaveRepository->listLeaves($userId);
    }

    public function getLeave(int $id): ?stdClass
    {
        return $this->leaveRepository->getLeave($id);
    }

    /**
     * @throws UserNotEmployeeException
     */
    public function createLeave(int $userId, string $dateFrom, string $dateTo, string $reason, DateTime $nowDateTime): bool
    {
        return $this->leaveRepository->createLeave($userId, $dateFrom, $dateTo, $reason, $nowDateTime);
    }

    public function updateLeave(int $leaveId, string $dateFrom, string $dateTo, string $reason, DateTime $nowDateTime): bool
    {
        return $this->leaveRepository->updateLeave($leaveId, $dateFrom, $dateTo, $reason, $nowDateTime);
    }

    public function deleteLeave(int $id): int
    {
        return $this->leaveRepository->deleteLeave($id);
    }
}
