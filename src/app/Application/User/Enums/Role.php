<?php

declare(strict_types = 1);

namespace App\Application\User\Enums;

enum Role: string
{
    case Employee = 'employee';
    case Manager  = 'manager';

    public function label(): string
    {
        return match ($this) {
            self::Employee => 'Employee',
            self::Manager  => 'Manager',
        };
    }
}
