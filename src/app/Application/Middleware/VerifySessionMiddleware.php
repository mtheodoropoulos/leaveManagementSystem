<?php

declare(strict_types = 1);

namespace App\Application\Middleware;

use App\Application\Router\RouterStrategyInterface;

class VerifySessionMiddleware
{
    public function handle(RouterStrategyInterface $router, callable $next): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit();
        }

        $next($router);
    }
}
