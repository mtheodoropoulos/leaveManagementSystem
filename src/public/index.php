<?php

use App\Application\Application;
use App\Application\Router\RouterFactory;
use App\Application\Router\RouterHandler;
use App\Application\Router\Routes;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$application = new Application(dirname(__DIR__));
$strategy    = RouterFactory::create('customRouter');
$router      = RouterHandler::getInstance($strategy);
$router->loadRoutes(Routes::getRoutes());
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri    = strtok($_SERVER['REQUEST_URI'], '?');

try {
    $router->dispatch($requestMethod, $requestUri);
} catch (ReflectionException $e) {
    throw new RuntimeException("Error while dispatching the request.");
}

