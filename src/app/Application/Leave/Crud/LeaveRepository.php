<?php

declare(strict_types = 1);

namespace App\Application\Leave\Crud;

use App\Application\Exceptions\UserNotEmployeeException;
use DateTime;
use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;
use stdClass;

class LeaveRepository implements LeaveRepositoryInterface
{
    public function __construct()
    {
    }

    public function listLeaves(int $userId): array
    {
        return Capsule::table('leaves')
                    ->join('employees', 'leaves.userId', '=', 'employees.userId')
                    ->join('users', 'employees.userId', '=', 'users.id')
                    ->where('leaves.userId', '=', $userId)
                    ->select(
                    'leaves.*',
                    'users.name as employee_name',
                    'users.email as employee_email',
                    'employees.employeeCode'
                    )
                    ->get()->toArray();
    }

    public function getLeave(int $id): ?stdClass
    {
        return Capsule::table('leaves')->where('id', '=', $id)->first();
    }

    /**
     * @throws UserNotEmployeeException
     */
    public function createLeave(int $userId, string $dateFrom, string $dateTo, string $reason, DateTime $nowDateTime): int
    {
        $employee = Capsule::table('employees')->where('userId', $userId)->first();

        if (!$employee) {
            throw new UserNotEmployeeException($userId);
        }

        return Capsule::table('leaves')->insertGetId([
            'userId'         => $userId,
            'date_requested' => $nowDateTime,
            'date_approved'  => null,
            'date_from'      => $dateFrom,
            'date_to'        => $dateTo,
            'reason'         => $reason,
            'status'         => 'pending',
            'approved_by'    => null,
            'created_at'     => $nowDateTime,
            'updated_at'     => $nowDateTime,
        ]);
    }
    public function updateLeave(int $leaveId, string $dateFrom, string $dateTo, string $reason, DateTime $nowDateTime): bool
    {
        try {
            $updated = Capsule::table('leaves')->where('id', $leaveId)->update([
                'date_from'  => $dateFrom,
                'date_to'    => $dateTo,
                'reason'     => $reason,
                'updated_at' => $nowDateTime,
            ]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function approveLeave(int $leaveId, DateTime $nowDateTime, int $managerId): bool
    {
        try {
            $updated = Capsule::table('leaves')->where('id', $leaveId)->update([
                'status'     => "approved",
                'date_approved'     => $nowDateTime,
                'updated_at' => $nowDateTime,
                'approved_by' => $managerId,
            ]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function rejectLeave(int $leaveId, DateTime $nowDateTime, int $managerId): bool
    {
        try {
            $updated = Capsule::table('leaves')->where('id', $leaveId)->update([
                'status'        => "rejected",
                'date_approved' => $nowDateTime,
                'updated_at'    => $nowDateTime,
                'approved_by'   => $managerId,
            ]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteLeave($id): int
    {
        return Capsule::table('leaves')->where('id', $id)->delete();
    }
}
