<?php
declare(strict_types=1);

namespace App\Core;

use Closure;

class Router
{
    private array $routes = [];

    public function get(string $path, mixed $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, mixed $handler, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    public function put(string $path, mixed $handler, array $middlewares = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middlewares);
    }

    public function delete(string $path, mixed $handler, array $middlewares = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middlewares);
    }

    private function addRoute(string $method, string $path, mixed $handler, array $middlewares): void
    {
        $normalizedPath = rtrim($path, '/') ?: '/';
        $this->routes[] = [
            'method' => $method,
            'path' => $normalizedPath,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch(Request $request): void
    {
        $requestMethod = $request->getMethod();
        $requestUri = $request->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['path']);
            $pattern = "#^{$pattern}$#";

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);

                // Ejecutar Middlewares
                foreach ($route['middlewares'] as $middlewareClass) {
                    if (class_exists($middlewareClass)) {
                        $middleware = new $middlewareClass();
                        if (method_exists($middleware, 'handle')) {
                            if ($middleware->handle($request) === false) {
                                return;
                            }
                        }
                    }
                }

                $handler = $route['handler'];

                // Controladores [Clase::class, 'metodo']
                if (is_array($handler) && count($handler) === 2) {
                    [$class, $method] = $handler;
                    if (class_exists($class)) {
                        $controller = new $class();
                        if (method_exists($controller, $method)) {
                            call_user_func_array([$controller, $method], array_merge([$request], $matches));
                            return;
                        }
                    }
                }

                // Funciones anónimas
                if ($handler instanceof Closure || is_callable($handler)) {
                    call_user_func_array($handler, array_merge([$request], $matches));
                    return;
                }
            }
        }

        if (str_starts_with($requestUri, '/api')) {
            Response::json(['success' => false, 'message' => 'Endpoint no encontrado'], 404);
        }

        Response::html('<h1>404 - Página no encontrada | J.A COLLECTION</h1>', 404);
    }
}