<?php

declare(strict_types = 1);

namespace App\Application\Middleware;

use App\Application\Router\RouterStrategyInterface;

class VerifySessionMiddleware
{
    public function handle(RouterStrategyInterface $router, array $payload, callable $next): void
    {
        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit();
        }

        $next($router, $payload);
    }
}
