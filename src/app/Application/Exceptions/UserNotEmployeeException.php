<?php

declare(strict_types = 1);

namespace App\Application\Exceptions;

use Exception;

class UserNotEmployeeException extends Exception
{
    public function __construct(int $userId, $code = 0, Exception $previous = null)
    {
        $message = "User with ID {$userId} is not an employee.";
        parent::__construct($message, $code, $previous);
    }
}
