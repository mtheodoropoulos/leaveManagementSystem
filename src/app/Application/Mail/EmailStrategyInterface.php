<?php

declare(strict_types = 1);

namespace App\Application\Mail;

use stdClass;

interface EmailStrategyInterface
{
    public function sendEmail(int $leaveId, $managerId, stdClass $user, $dateFrom, $dateTo, $reason, $csrfToken): bool;
}
