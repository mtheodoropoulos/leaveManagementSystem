<?php

declare(strict_types = 1);

namespace App\Application\Router;

interface RouterInterface
{
    public static function getRouter(): self;

    public function add(string $method, string $path, string $controller, string $action): void;

    public function callController(string $controller, string $action, array $params, array $payload): void;

    public function dispatch(string $method, string $uri): void;

}
