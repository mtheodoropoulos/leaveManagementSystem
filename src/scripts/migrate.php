<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
//    'host' => '127.0.0.1',
    'host'      => 'laravel-mysql',
    'database'  => 'db_main',
    'username'  => 'mixalis',
    'password'  => 'theodoropo',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$connectionResolver = new class($capsule) implements ConnectionResolverInterface{
    protected Capsule $capsule;

    public function __construct($capsule)
    {
        $this->capsule = $capsule;
    }

    public function connection($name = null): \Illuminate\Database\Connection
    {
        return $this->capsule->getConnection();
    }

    public function getDefaultConnection()
    {
        return $this->capsule->getDefaultConnection();
    }

    public function setDefaultConnection($name): void
    {
    }
};

$repository = new DatabaseMigrationRepository($connectionResolver, 'migrations');

if (!$repository->repositoryExists()) {
    $repository->createRepository();
}

require __DIR__ . '/../database/migrations/create_users_table.php';
require __DIR__ . '/../database/migrations/create_roles_table.php';
require __DIR__ . '/../database/migrations/create_role_user_table.php';
require __DIR__ . '/../database/migrations/create_permissions_table.php';
require __DIR__ . '/../database/migrations/create_role_permission_table.php';
require __DIR__ . '/../database/migrations/create_leaves_table.php';

$migrator = new Migrator($repository, $connectionResolver, new Filesystem());

$migrator->run();

echo "Migrations run successfully.\n";
