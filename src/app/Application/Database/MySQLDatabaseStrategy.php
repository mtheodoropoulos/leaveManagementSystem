<?php

declare(strict_types = 1);

namespace App\Application\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class MySQLDatabaseStrategy implements DatabaseStrategyInterface
{
    public function __construct(private readonly Capsule $capsule)
    {
    }

    public function connect(array $config): void
    {
        $this->capsule->addConnection([
            'driver'    => $config['DB_CONNECTION'],
            'host'      => $config['DB_HOST'],
            'database'  => $config['DB_DATABASE'],
            'username'  => $config['DB_USERNAME'],
            'password'  => $config['DB_PASSWORD'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }
}
