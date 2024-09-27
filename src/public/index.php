<?php

use App\Application\Application;
use App\Application\Database\DatabaseHandler;
use App\Application\Router\Router;
use App\Application\Router\Routes;
use Illuminate\Support\Facades\Facade;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$application = new Application(dirname(__DIR__));
$database    = DatabaseHandler::getInstance();
Facade::setFacadeApplication($application);
$router      = Router::getRouter();
$router->loadRoutes(Routes::getRoutes());
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri    = strtok($_SERVER['REQUEST_URI'], '?');

try {
    $router->dispatch($requestMethod, $requestUri);
} catch (ReflectionException $e) {
    throw new RuntimeException("Error while dispatching the request.");
}

