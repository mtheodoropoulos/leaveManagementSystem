<?php

declare(strict_types = 1);

namespace App\Application\Mail;

use stdClass;

class MailHandler
{
    private EmailStrategyInterface $mailStrategy;
    public function __construct(EmailStrategyInterface $mailStrategy)
    {
        $this->mailStrategy = $mailStrategy;
    }

    public function sendEmail(int $leaveId, $managerId, stdClass $user, $dateFrom, $dateTo, $reason, $csrfToken): bool
    {
        return $this->mailStrategy->sendEmail($leaveId, $managerId, $user, $dateFrom, $dateTo, $reason, $csrfToken);
    }
}
