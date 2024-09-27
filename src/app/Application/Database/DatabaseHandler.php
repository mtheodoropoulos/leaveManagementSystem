<?php

declare(strict_types = 1);

namespace App\Application\Database;

use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DatabaseHandler
{
    private string $basePath;
    private Capsule $capsule;
    private static DatabaseHandler $databaseHandler;

    public function __construct(Capsule $capsule)
    {
        $this->capsule  = $capsule;
        $this->basePath = '/var/www/';
        $dotenv         = Dotenv::createImmutable($this->basePath);
        $dotenv->load();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getInstance(): self
    {

        $databaseHandlerInstance = Container::getInstance()->get(__CLASS__);

        if (!isset(self::$databaseHandler)) {

            self::$databaseHandler = $databaseHandlerInstance;
        }

        return self::$databaseHandler;
    }

    public function connect(): void
    {
        $this->capsule->addConnection([
            'driver'    => $_ENV['DB_CONNECTION'],
            'host'      => $_ENV['DB_HOST'],
            'database'  => $_ENV['DB_DATABASE'],
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }
}
