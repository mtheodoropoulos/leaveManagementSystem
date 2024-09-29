<?php

declare(strict_types = 1);

namespace App\Application\Mail;

use Illuminate\Container\Container;
use PHPMailer\PHPMailer\PHPMailer;

class MailerFactory
{
    public static function create(string $type): EmailStrategyInterface
    {
        return match ($type) {
            'mailgun' => Container::getInstance()->get(MailgunEmailStrategy::class),
            default     => throw new \InvalidArgumentException("Unsupported mailer type: {$type}"),
        };
    }
}
