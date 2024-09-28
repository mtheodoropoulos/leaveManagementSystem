<?php

declare(strict_types = 1);

namespace App\Application\http\Controllers;

use App\Application\http\Controllers\Base\BaseController;
use App\Application\User\Enums\Role;
use App\Application\User\Services\Crud\UserService;
use App\Application\View\View;
use DateTime;

class UserController extends BaseController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function listUsers(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $csrfToken             = $this->csrfToken();
        $_SESSION['csrfToken'] = $csrfToken;

        $loggedInUser     = $this->userService->getUser($_SESSION['user']);
        $users            = $this->userService->listUsers();
        $loggedInUserName = $loggedInUser?->name;

        $view = new View('users/list.php', [
            'csrfToken'        => $csrfToken,
            'heading'          => 'Users list',
            'users'            => $users,
            'loggedInUserName' => $loggedInUserName,
        ]);
        echo $view->render();
    }

    public function showCreateUser(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $csrfToken             = $this->csrfToken();
        $_SESSION['csrfToken'] = $csrfToken;

        $view = new View('users/createUser.php', [
            'csrfToken' => $csrfToken,
            'heading'   => 'Create User',
        ]);

        echo $view->render();
    }

    /**
     * @throws \JsonException
     */
    public function createUser(array $payload): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $name         = $payload['name'];
        $email        = $payload['email'];
        $password     = $payload['password'];
        $employeeCode = $payload['employeeCode'];
        $nowDateTime  = new DateTime('now');
        $roleName     = Role::Employee;

        $result = $this->userService->createUser($name, $email, $password, $employeeCode, $nowDateTime, $roleName);

        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'User created successfully', "status" => 200], JSON_THROW_ON_ERROR);
            header('Location: /listUsers');
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'User does not exist or incorrect password', 'status' => 400], JSON_THROW_ON_ERROR);
            header('Location: /listUsers');
        }
    }
}
