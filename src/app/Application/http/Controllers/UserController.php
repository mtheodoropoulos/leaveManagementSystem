<?php

declare(strict_types = 1);

namespace App\Application\http\Controllers;

use App\Application\http\Controllers\Base\BaseController;
use App\Application\User\Enums\Role;
use App\Application\User\Services\Crud\UserService;
use App\Application\View\View;
use DateTime;
use Illuminate\Database\Capsule\Manager as Capsule;

class UserController extends BaseController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function listUsers(): void
    {
        $csrfToken             = $this->csrfToken();
        $_SESSION['csrfToken'] = $csrfToken;

        $loggedInUser     = $this->userService->getUser($_SESSION['userId']);
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
        $name         = $payload['name'];
        $email        = $payload['email'];
        $password     = $payload['password'];
        $password     = password_hash($password, PASSWORD_DEFAULT);
        $employeeCode = $payload['employeeCode'];
        $nowDateTime  = new DateTime('now');
        $roleName     = Role::Employee;
        $loggedInUser = $this->userService->getUser($_SESSION['userId']);

        if (!$loggedInUser) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized', 'status' => 401], JSON_THROW_ON_ERROR);
            return;
        }

        $result = $this->userService->createUser($name, $email, $password, $employeeCode, $nowDateTime, $roleName, $loggedInUser->id);

        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'User created successfully', "status" => 200], JSON_THROW_ON_ERROR);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'User does not exist or incorrect password', 'status' => 400], JSON_THROW_ON_ERROR);
        }
    }

    public function editUser($id): void
    {
        $user = $this->userService->getUserWithEmployeeCode((int)$id);

        if ($user) {
            $csrfToken             = $this->csrfToken();
            $_SESSION['csrfToken'] = $csrfToken;
            $heading = "Edit User";

            $view = new View('users/editUser.php', [
                'csrfToken' => $csrfToken,
                'heading'   => $heading,
                'user'      => $user,
            ]);

            echo $view->render();
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not found', 'status' => 404], JSON_THROW_ON_ERROR);
        }
    }

    /**
     * @throws \JsonException
     */
    public function updateUser(int $id, array $payload): void
    {
        $name = $payload['name'];
        $email = $payload['email'];
        $employeeCode = (int)$payload['employeeCode'];
        $nowDateTime  = new DateTime('now');

        $result = $this->userService->updateUser($id, $name, $email, $employeeCode, $nowDateTime);

        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'User updated successfully!', 'status' => 200], JSON_THROW_ON_ERROR);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Failed to update user', 'status' => 400], JSON_THROW_ON_ERROR);
        }
    }

    /**
     * @throws \JsonException
     */
    public function deleteUser(int $id, array $payload): void
    {
        $result = $this->userService->deleteUser($id);

        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'User deleted successfully', 'status' => 200], JSON_THROW_ON_ERROR);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not found', 'status' => 404], JSON_THROW_ON_ERROR);
        }
    }
}
