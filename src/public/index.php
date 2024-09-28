<?php

use App\Application\Application;
use App\Application\Database\DatabaseFactory;
use App\Application\Database\DatabaseHandler;
use App\Application\Router\RouterFactory;
use App\Application\Router\RouterHandler;
use App\Application\Router\Routes;
use Illuminate\Support\Facades\Facade;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$application = new Application(dirname(__DIR__));
Facade::setFacadeApplication($application);

try {
    $strategy = DatabaseFactory::create('mysql');
    DatabaseHandler::getInstance($strategy)->connect();

    $strategy = RouterFactory::create('customRouter');
    $router   = RouterHandler::getInstance($strategy);
    $router->loadRoutes(Routes::getRoutes());

    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $requestUri    = strtok($_SERVER['REQUEST_URI'], '?');

    $router->dispatch($requestMethod, $requestUri);
} catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
    throw new RuntimeException("Error while dispatching the request.");
}

