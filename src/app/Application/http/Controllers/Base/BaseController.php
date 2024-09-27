<?php

declare(strict_types = 1);

namespace App\Application\http\Controllers\Base;

class BaseController
{
    public function csrfToken(): string
    {
        return $_SESSION['csrfToken'] ?? ($_SESSION['csrfToken'] = bin2hex(random_bytes(32)));
    }
}
