<?php

declare(strict_types = 1);

namespace App\Application\Database;

interface DatabaseStrategyInterface
{
    public function connect(array $config): void;

}
