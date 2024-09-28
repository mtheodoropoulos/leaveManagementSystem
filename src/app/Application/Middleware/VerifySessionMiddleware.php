<?php

declare(strict_types = 1);

namespace App\Application\Middleware;

class VerifySessionMiddleware
{
    public function handle($request, $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit();
        }

        return $next($request);
    }
}
