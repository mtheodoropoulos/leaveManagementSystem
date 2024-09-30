<?php

declare(strict_types = 1);

namespace Tests\Unit;

use App\Application\http\Controllers\UserController;
use App\Application\User\Services\Crud\UserService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class UserControllerTest extends TestCase
{
    private MockObject $userService;
    private UserController $userController;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->userService    = $this->createMock(UserService::class);
        $this->userController = new UserController($this->userService);
    }

    public function testCreateUserSuccess(): void
    {
        $payload = [
            'name'         => 'John Doe',
            'email'        => 'john@example.com',
            'password'     => 'password123',
            'employeeCode' => '1234567',
        ];

        $_SESSION['userId'] = 1;

        $loggedInUser     = new stdClass();
        $loggedInUser->id = 1;
        $this->userService->expects($this->once())->method('getUser')->with(1)->willReturn($loggedInUser);

        $this->userService->expects($this->once())->method('createUser')->willReturn(true);
    }
}
