<?php

declare(strict_types = 1);

namespace App\Application\http\Controllers;

use App\Application\Exceptions\UserNotEmployeeException;
use App\Application\http\Controllers\Base\BaseController;
use App\Application\Leave\Services\Crud\LeaveService;
use App\Application\Mail\MailerFactory;
use App\Application\Mail\MailHandler;
use App\Application\User\Services\Crud\UserService;
use App\Application\View\View;
use DateTime;
use Exception;
use JsonException;
use PHPMailer\PHPMailer\PHPMailer;
use stdClass;

class LeaveController extends BaseController
{
    public function __construct(
        private readonly LeaveService $leaveService,
        private readonly UserService  $userService
    ) {
    }

    public function listLeaves(): void
    {
        $csrfToken             = $this->csrfToken();
        $_SESSION['csrfToken'] = $csrfToken;

        $loggedInUser     = $this->userService->getUser($_SESSION['userId']);
        $loggedInUserName = $loggedInUser?->name;
        $leaves           = $this->leaveService->listLeaves((int)$loggedInUser?->id);

        $view = new View('leaves/leavesList.php', [
            'csrfToken'        => $csrfToken,
            'heading'          => 'Leaves list',
            'leaves'            => $leaves,
            'loggedInUserName' => $loggedInUserName,
        ]);

        echo $view->render();
    }

    public function showCreateLeave(): void
    {
        $csrfToken             = $this->csrfToken();
        $_SESSION['csrfToken'] = $csrfToken;

        $view = new View('leaves/createLeave.php', [
            'csrfToken' => $csrfToken,
            'heading'   => 'Create Leave',
        ]);

        echo $view->render();
    }

    /**
     * @throws JsonException
     */
    public function createLeave(array $payload): void
    {
        $dateFrom              = $payload['date_from'];
        $dateTo                = $payload['date_to'];
        $reason                = $payload['reason'];
        $nowDateTime           = new DateTime('now');
        $loggedInUser          = $this->userService->getUser($_SESSION['userId']);
        $csrfToken             = $this->csrfToken();
        $_SESSION['csrfToken'] = $csrfToken;

        if (!$loggedInUser) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized', 'status' => 401], JSON_THROW_ON_ERROR);

            return;
        }

        try {
            $leaveId = $this->leaveService->createLeave((int)$loggedInUser->id, $dateFrom, $dateTo, $reason, $nowDateTime);
            $manager    = $this->userService->getUserWithCreatedBy($loggedInUser->id);
            $managerId  = $manager?->created_by;

            $this->sendLeaveRequestEmail($leaveId, $managerId, $loggedInUser, $dateFrom, $dateTo, $reason, $csrfToken);

            http_response_code(200);
            echo json_encode(['message' => 'User created successfully', "status" => 200], JSON_THROW_ON_ERROR);
        } catch (UserNotEmployeeException $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage(), 'status' => 400], JSON_THROW_ON_ERROR);
        }
    }

    private function sendLeaveRequestEmail(int $leaveId, $managerId, stdClass $user, $dateFrom, $dateTo, $reason, $csrfToken): void
    {

        $mailerStrategy = MailerFactory::create('mailgun');
        $mailerHandler = new MailHandler($mailerStrategy);
        $mailerHandler->sendEmail($leaveId, $managerId, $user, $dateFrom, $dateTo, $reason, $csrfToken);
    }

    public function approveLeave($id, $managerId): void
    {
        $leave       = $this->leaveService->getLeave((int)$id);
        $nowDateTime = new DateTime('now');

        if (!$leave) {
            http_response_code(404);
            echo json_encode(['message' => 'Leave request not found', 'status' => 404], JSON_THROW_ON_ERROR);

            return;
        }

        if ($leave->status === 'approved') {
            http_response_code(400);
            echo json_encode(['message' => 'Leave request is already approved', 'status' => 400], JSON_THROW_ON_ERROR);
            return;
        }

        try {
            $leaveId = $this->leaveService->approveLeave($leave->id, $nowDateTime, (int)$managerId);

            http_response_code(200);
            echo json_encode(['message' => 'Leave request approved successfully', 'status' => 200], JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while approving the leave request', 'status' => 500], JSON_THROW_ON_ERROR);
        }
    }

    public function rejectLeave($id, $managerId): void
    {
        $leave       = $this->leaveService->getLeave((int)$id);
        $nowDateTime = new DateTime('now');

        if (!$leave) {
            http_response_code(404);
            echo json_encode(['message' => 'Leave request not found', 'status' => 404], JSON_THROW_ON_ERROR);

            return;
        }

        if ($leave->status === 'rejected') {
            http_response_code(400);
            echo json_encode(['message' => 'Leave request is already rejected', 'status' => 400], JSON_THROW_ON_ERROR);
            return;
        }

        try {
            $leaveId = $this->leaveService->rejectLeave($leave->id, $nowDateTime, (int)$managerId);

            http_response_code(200);
            echo json_encode(['message' => 'Leave request rejected successfully', 'status' => 200], JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while rejecting the leave request', 'status' => 500], JSON_THROW_ON_ERROR);
        }
    }

    public function editLeave($id): void
    {
        $leave = $this->leaveService->getLeave((int)$id);

        if ($leave) {
            $csrfToken             = $this->csrfToken();
            $_SESSION['csrfToken'] = $csrfToken;
            $heading               = "Edit Leave";

            $view = new View('leaves/editLeave.php', [
                'csrfToken' => $csrfToken,
                'heading'   => $heading,
                'leave'     => $leave,
            ]);

            echo $view->render();
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Leave not found', 'status' => 404], JSON_THROW_ON_ERROR);
        }
    }

    public function updateLeave(int $id, array $payload): void
    {
        $dateFrom    = $payload['date_from'];
        $dateTo      = $payload['date_to'];
        $reason      = $payload['reason'];
        $nowDateTime = new DateTime('now');

        $result = $this->leaveService->updateLeave($id, $dateFrom, $dateTo, $reason, $nowDateTime);

        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'User updated successfully!', 'status' => 200], JSON_THROW_ON_ERROR);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Failed to update user', 'status' => 400], JSON_THROW_ON_ERROR);
        }
    }

    public function deleteLeave(int $id, array $payload): void
    {
        $result = $this->leaveService->deleteLeave($id);

        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'Leave deleted successfully', 'status' => 200], JSON_THROW_ON_ERROR);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Leave not found', 'status' => 404], JSON_THROW_ON_ERROR);
        }
    }
}
