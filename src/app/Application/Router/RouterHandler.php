<?php

declare(strict_types = 1);

namespace App\Application\Router;

class RouterHandler
{
    private RouterStrategyInterface $strategy;

    private static ?RouterHandler $routerHandler = null;

    public function __construct(RouterStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }
    public static function getInstance(RouterStrategyInterface $strategy): self
    {
        if (!isset(self::$routerHandler)) {
            self::$routerHandler = new self($strategy);
        }

        return self::$routerHandler;
    }

    public function loadRoutes(array $routes): void
    {
        $this->strategy->loadRoutes($routes);
    }

    public function dispatch(string $method, string $uri): void
    {
        $this->strategy->dispatch($method, $uri);
    }
}
