<?php

declare(strict_types = 1);

namespace App\Application\Router;

use Illuminate\Container\Container;
use ReflectionMethod;
use RuntimeException;

class CustomRouterStrategy implements RouterStrategyInterface
{
    private static CustomRouterStrategy $router;
    private array $routes = [];

    public static function getRouter(): self
    {

        if (!isset(self::$router)) {

            self::$router = new self();
        }

        return self::$router;
    }

    public function add(string $method, string $path, string $controller, string $action, ?array $middleware): void
    {
        $path           = preg_replace('/{([\w]+)}/', '(?P<\1>[\w-]+)', $path);
        $this->routes[] = [
            'method'     => strtoupper($method),
            'path'       => '#^' . $path . '$#',
            'controller' => $controller,
            'action'     => $action,
            'middleware' => $middleware
        ];
    }

    public function loadRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            $this->add($route['method'], $route['path'], $route['controller'], $route['action'], $route['middleware'] ?? null);
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

                if (isset($route['middleware'])) {
                    $middlewares = $route['middleware'];
                    $this->handleMiddlewares($middlewares, $route, $matches, $payload);
                    return;
                }

                $this->callController($route['controller'], $route['action'], $matches, $payload);

                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    private function handleMiddlewares(array $middlewares, array $route, array $matches, array $payload): void
    {
        $next = function($router, $payload) use ($route, $matches) {
            $this->callController($route['controller'], $route['action'], $matches, $payload);
        };

        foreach (array_reverse($middlewares) as $middlewareClass) {
            $middleware = new $middlewareClass();

            $next = static function($router, $payload) use ($middleware, $next) {
                $middleware->handle($router, $payload, $next);
            };
        }

        $next($this, $payload);
    }

    public function callController(string $controller, string $action, array $params, array $payload): void
    {
        $container = Container::getInstance();

        if ($container->has($controller) || class_exists($controller)) {
            $controllerInstance = $container->make($controller);

            if (method_exists($controllerInstance, $action)) {
                // Filter out the named parameters from the regex matches
                $params = array_filter($params, 'is_string', ARRAY_FILTER_USE_KEY);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (!empty($params)) {
                        $arguments = ["payload" => $payload, ...$params];
                    } else {
                        $arguments = ["payload" => $payload];
                    }
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

    private function getPostPayload(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
                $jsonPayload = file_get_contents('php://input');

                return json_decode($jsonPayload, true) ?? [];
            }

            return $_POST;
        }

        return [];
    }
}
