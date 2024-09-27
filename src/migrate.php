<?php

declare(strict_types = 1);

require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
//    'host' => '127.0.0.1', // Adjust as needed
    'host' => 'laravel-mysql', // Adjust as needed
    'database' => 'db_main',
    'username' => 'mixalis',
    'password' => 'theodoropo',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

// Make this Capsule instance available globally
$capsule->setAsGlobal();
$capsule->bootEloquent();


$connectionResolver = new class($capsule) implements ConnectionResolverInterface{
    protected $capsule;

    public function __construct($capsule)
    {
        $this->capsule = $capsule;
    }

    public function connection($name = null)
    {
        return $this->capsule->getConnection();
    }

    public function getDefaultConnection()
    {
        return $this->capsule->getDefaultConnection();
    }

    public function setDefaultConnection($name)
    {
    }
};

// Step 3: Set up migration repository
$repository = new DatabaseMigrationRepository($connectionResolver, 'migrations');

// Step 4: Check if the migrations table exists, create it if not
if (!$repository->repositoryExists()) {
    $repository->createRepository();
}

require 'database/migrations/create_users_table.php';

$migrator = new Migrator($repository, $connectionResolver, new Filesystem());

$migrator->run();

echo "Migrations run successfully.\n";
