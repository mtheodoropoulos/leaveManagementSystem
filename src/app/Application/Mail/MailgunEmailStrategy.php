<?php

declare(strict_types = 1);

namespace App\Application\Mail;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use stdClass;

class MailgunEmailStrategy implements EmailStrategyInterface
{
    private $mailer;

    public function __construct(PHPMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(int $leaveId, $managerId, stdClass $user, $dateFrom, $dateTo, $reason, $csrfToken): bool
    {
        $approvalUrl = "http://localhost/approveLeave/{$leaveId}/manager/$managerId?csrfToken={$csrfToken}";
        $rejectionUrl = "http://localhost/rejectLeave/{$leaveId}/manager/{$managerId}?csrfToken={$csrfToken}";

        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = 'smtp.mailgun.org';
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username   = 'postmaster@sandboxYOUR_DOMAIN.mailgun.org';
            $this->mailer->Password   = 'b99831ba7a1c661d3645025a7781e663-1b5736a5-c3fc828b';
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port       = 587;

            $this->mailer->setFrom('me@example.com', 'Leave Management System');
            $this->mailer->addAddress('theodoropoceid@gmail.com', 'Manager');
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'New Leave Request from ' . htmlspecialchars($user->name);
            $this->mailer->Body    = "
                <h3>Leave Request Details</h3>
                <p><strong>Employee:</strong> " . htmlspecialchars($user->name) . "</p>
                <p><strong>Date From:</strong> $dateFrom</p>
                <p><strong>Date To:</strong> $dateTo</p>
                <p><strong>Reason:</strong> $reason</p>
                <p>Please approve or reject the leave request.</p>
                <p>
                    Please <a href='{$approvalUrl}'>click here</a> to approve the leave request.<br>
                    Or <a href='{$rejectionUrl}'>click here</a> to reject the leave request.
                </p>
            ";

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Email could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
}
