<?php

declare(strict_types = 1);

namespace App\Application\http\Controllers;

use App\Application\http\Controllers\Base\BaseController;
use App\Application\View\View;

class UserController extends BaseController
{
    public function listUsers(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $csrfToken             = $this->csrfToken();
        $_SESSION['csrfToken'] = $csrfToken;

        $view = new View('users/list.php', [
            'csrfToken' => $csrfToken,
            'heading'   => 'Users list',
        ]);
        echo $view->render();
    }
}
