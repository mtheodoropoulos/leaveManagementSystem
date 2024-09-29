<?php

declare(strict_types = 1);

namespace App\Application\http\Controllers;

use App\Application\http\Controllers\Base\BaseController;
use App\Application\User\Enums\Role;
use App\Application\User\Services\Crud\UserService;
use App\Application\Utils\CommonFunctionsUtils;
use App\Application\View\View;
use DateTime;
use Illuminate\Database\Capsule\Manager as Capsule;
use JetBrains\PhpStorm\NoReturn;
use Random\RandomException;

class AuthController extends BaseController
{

    public function __construct(private readonly UserService $userService)
    {
    }

    public function showLogin(): void
    {
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
     * @throws RandomException
     */
    #[NoReturn] public function postRegister(array $payload): void
    {
        $name        = $payload['name'];
        $email       = $payload['email'];
        $password    = $payload['password'];
        $password    = password_hash($password, PASSWORD_DEFAULT);
        $nowDateTime = new DateTime('now');
        $roleName    = Role::Employee;

        $sevenDigitNumber = (string)CommonFunctionsUtils::generateRandom7DigitNumber();

        $userId = $this->userService->createUser($name, $email, $password, $sevenDigitNumber, $nowDateTime, $roleName);

        if ($userId) {
            $_SESSION['userId'] = $userId;
            $_SESSION['$email'] = $email;
            http_response_code(200);
            echo json_encode(['message' => 'Registration successful!', "status" => 200], JSON_THROW_ON_ERROR);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'User does not exist or incorrect password', 'status' => 400], JSON_THROW_ON_ERROR);
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
            $_SESSION['userId'] = $user->id;
            $_SESSION['$email'] = $user->email;

            $role =  $this->userService->getUserRole($user);

            if ($role && $role->name === "manager") {
                header('Location: /listUsers');
            }

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
