<?php

declare(strict_types = 1);

namespace App\Application\Database;

use Dotenv\Dotenv;

class DatabaseHandler
{
    private string $basePath;
    private DatabaseStrategyInterface $strategy;
    private static ?DatabaseHandler $databaseHandler = null;

    public function __construct(DatabaseStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
        $this->basePath = '/var/www/';
        $dotenv         = Dotenv::createImmutable($this->basePath);
        $dotenv->load();
    }

    public static function getInstance(DatabaseStrategyInterface $strategy): self
    {
        if (!isset(self::$databaseHandler)) {
            self::$databaseHandler = new self($strategy);
        }

        return self::$databaseHandler;
    }

    public function connect(): void
    {
        $config = [
            'DB_CONNECTION' => $_ENV['DB_CONNECTION'],
            'DB_HOST'       => $_ENV['DB_HOST'],
            'DB_DATABASE'   => $_ENV['DB_DATABASE'],
            'DB_USERNAME'   => $_ENV['DB_USERNAME'],
            'DB_PASSWORD'   => $_ENV['DB_PASSWORD'],
        ];

        $this->strategy->connect($config);
    }
}
