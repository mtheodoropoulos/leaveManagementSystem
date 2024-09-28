<?php

declare(strict_types = 1);

namespace App\Application\Router;

use Illuminate\Container\Container;
use InvalidArgumentException;

class RouterFactory
{
    public static function create(string $routerTyoe): RouterStrategyInterface
    {
        return match ($routerTyoe) {
            'customRouter' => Container::getInstance()->get(CustomRouterStrategy::class),
            default => throw new InvalidArgumentException("Unsupported Router type: {$routerTyoe}"),
        };
    }
}
