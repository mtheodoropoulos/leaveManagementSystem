<?php

declare(strict_types = 1);

namespace App\Application\Database;

use Illuminate\Container\Container;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DatabaseFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function create(string $driver): DatabaseStrategyInterface
    {
        return match ($driver) {
            'mysql' => Container::getInstance()->get(MySQLDatabaseStrategy::class),
            default => throw new \InvalidArgumentException("Unsupported database driver: {$driver}"),
        };
    }
}
