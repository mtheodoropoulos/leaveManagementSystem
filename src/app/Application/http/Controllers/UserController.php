<?php

declare(strict_types = 1);

namespace App\Application\http\Controllers;

use App\Application\http\Controllers\Base\BaseController;
use App\Application\User\Services\Crud\UserService;
use App\Application\View\View;

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

        $users = $this->userService->listUsers();

        $view = new View('users/list.php', [
            'csrfToken' => $csrfToken,
            'heading'   => 'Users list',
            'users'     => $users,
        ]);
        echo $view->render();
    }
}
