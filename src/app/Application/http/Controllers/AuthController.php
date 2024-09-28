<?php

declare(strict_types = 1);

namespace App\Application\http\Controllers;

use App\Application\http\Controllers\Base\BaseController;
use App\Application\User\Services\Crud\UserService;
use App\Application\View\View;
use DateTime;
use Illuminate\Database\Capsule\Manager as Capsule;
use JetBrains\PhpStorm\NoReturn;

class AuthController extends BaseController
{

    public function __construct(private readonly UserService $userService)
    {

    }

    public function showLogin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $csrfToken             = $this->csrfToken();
        $_SESSION['csrfToken'] = $csrfToken;

        $view = new View('auth/login.php', [
            'csrfToken' => $csrfToken,
            'heading'   => 'Login',
        ]);
        echo $view->render();
    }

    public function showRegister(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $csrfToken             = $this->csrfToken();
        $_SESSION['csrfToken'] = $csrfToken;

        $view = new View('auth/register.php', [
            'csrfToken' => $csrfToken,
            'heading'   => 'Register',
        ]);
        echo $view->render();
    }

    /**
     * @throws \JsonException
     */
    #[NoReturn] public function postRegister(array $payload): void
    {
        if (hash_equals($_SESSION['csrfToken'], $payload['csrfToken'])) {
            $name        = $payload['name'];
            $email       = $payload['email'];
            $password    = $payload['password'];
            $password    = password_hash($password, PASSWORD_DEFAULT);
            $nowDateTime = new DateTime('now');

            $result = $this->userService->registerUser($name, $email, $password, $nowDateTime);

            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Registration successful!', "status" => 200], JSON_THROW_ON_ERROR);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'User does not exist or incorrect password', 'status' => 400], JSON_THROW_ON_ERROR);
            }
        }

        exit;
    }

    /**
     * @throws \JsonException
     */
    #[NoReturn] public function postLogin(array $payload): void
    {
        $email    = $payload['email'];
        $password = $payload['password'];

        $user = Capsule::table('users')->where('email', $email)->first();

        if ($user && password_verify($password, $user->password)) {
            session_start();
            $_SESSION['user'] = $user;
            header('Location: /listUsers');
            exit;
        }

        http_response_code(400);
        echo json_encode(['error' => 'User does not exist or incorrect password', 'status' => 400], JSON_THROW_ON_ERROR);
        exit;
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }

}
