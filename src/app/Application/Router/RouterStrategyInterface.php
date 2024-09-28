<?php

declare(strict_types = 1);

namespace App\Application\Router;

interface RouterStrategyInterface
{
    public static function getRouter(): self;

    public function add(string $method, string $path, string $controller, string $action, string $middleware): void;

    public function loadRoutes(array $routes): void;

    public function dispatch(string $method, string $uri): void;

    public function callController(string $controller, string $action, array $params, array $payload): void;
}
