<?php

declare(strict_types = 1);

namespace App\Application\Middleware;

use App\Application\Router\RouterStrategyInterface;

class VerifyCsrfMiddleware
{
    public function handle(RouterStrategyInterface $router, array $payload, callable $next): void
    {
        $csrfToken = $payload['csrfToken'] ?? '';

        if (empty($csrfToken) || !hash_equals($_SESSION['csrfToken'] ?? '', $csrfToken)) {
            http_response_code(403);
            echo "CSRF token validation failed.";
            return;
        }

        $next($router, $payload);
    }
}
