<?php

declare(strict_types = 1);

namespace App\Application\Router;

use Illuminate\Container\Container;
use ReflectionMethod;
use RuntimeException;

class Router implements RouterInterface
{
    private static Router $router;
    private array $routes = [];

    public static function getRouter(): self
    {

        if (!isset(self::$router)) {

            self::$router = new self();
        }

        return self::$router;
    }

    public function add(string $method, string $path, string $controller, string $action): void
    {
        $path           = preg_replace('/{([\w]+)}/', '(?P<\1>[\w-]+)', $path);
        $this->routes[] = [
            'method'     => strtoupper($method),
            'path'       => '#^' . $path . '$#',
            'controller' => $controller,
            'action'     => $action
        ];
    }

    public function loadRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            $this->add($route['method'], $route['path'], $route['controller'], $route['action']);
        }
    }

    public function dispatch(string $method, string $uri): void
    {
        if ($uri === '/') {
            $uri = '/login';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($method) && preg_match($route['path'], $uri, $matches)) {
                $payload = $this->getPostPayload();

                $this->callController($route['controller'], $route['action'], $matches, $payload);

                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

// Function to get POST payload
    private function getPostPayload(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Attempt to get JSON payload if content type is application/json
            if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                $jsonPayload = file_get_contents('php://input');

                return json_decode($jsonPayload, true) ?? [];
            }

            // Otherwise, return the regular POST data
            return $_POST;
        }

        return [];
    }

    public function callController(string $controller, string $action, array $params, array $payload): void
    {
        $container = Container::getInstance();

        if ($container->has($controller) || class_exists($controller)) {
            $controllerInstance = $container->make($controller);

            if (method_exists($controllerInstance, $action)) {
                // Filter out the named parameters from the regex matches
                $params = array_filter($params, 'is_string', ARRAY_FILTER_USE_KEY);

                // Prepare arguments based on request method
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $arguments = ["payload" => $payload];
                } else {
                    $arguments = $params;
                }

                $reflectionMethod = new ReflectionMethod($controllerInstance, $action);
                $reflectionMethod->invokeArgs($controllerInstance, $arguments);
            } else {
                throw new RuntimeException("Method $action not found in controller $controller");
            }
        } else {
            throw new RuntimeException("Controller $controller not found");
        }
    }
}
