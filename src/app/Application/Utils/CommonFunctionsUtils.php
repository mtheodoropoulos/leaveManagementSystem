<?php

declare(strict_types = 1);

namespace App\Application\Utils;

use Random\RandomException;

class CommonFunctionsUtils
{
    /**
     * @throws RandomException
     */
    public static function generateRandom7DigitNumber(): int
    {
        return random_int(1000000, 9999999);
    }
}
