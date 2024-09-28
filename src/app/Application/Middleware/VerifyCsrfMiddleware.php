<?php

declare(strict_types = 1);

namespace App\Application\Middleware;

use App\Application\Router\RouterStrategyInterface;

class VerifyCsrfMiddleware
{
    public function handle(RouterStrategyInterface $router, callable $next): void
    {
        $csrfToken = $_POST['csrfToken'] ?? '';

        if (empty($csrfToken) || !hash_equals($_SESSION['csrfToken'] ?? '', $csrfToken)) {
            http_response_code(403);
            echo "CSRF token validation failed.";
            return;
        }

        $next($router);
    }
}
