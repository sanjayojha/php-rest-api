<?php

namespace Sanjayojha\PhpRestApi\Core;

use Sanjayojha\PhpRestApi\Enum\HTTPMethod;
use Sanjayojha\PhpRestApi\Controllers\Controller;
use Sanjayojha\PhpRestApi\Middleware\Middleware;
use Sanjayojha\PhpRestApi\Middleware\Auth;
use Sanjayojha\PhpRestApi\Exception\HTTPException\HTTPNotFoundException;

class Router
{
    protected array $routes = [];

    public function addRoute(HTTPMethod $method, string $path, Controller $controller): self
    {
        $this->routes[] = [
            "method" => $method->value,
            "path" => $path,
            "controller" => $controller,
            "middleware" => []
        ];

        return $this;
    }

    public function addMiddlewares(array $middlewares): void
    {
        $lastRoute = count($this->routes) - 1;
        $this->routes[$lastRoute]["middleware"] = $middlewares;
    }

    public function dispatch(Request $request): never
    {
        $path = $request->getPath();
        $method = HTTPMethod::from($request->getMethod());

        foreach ($this->routes as $route) {
            if ($route["path"] === $path && $route["method"] === $method->value) {
                $middlewares = $route["middleware"];
                $this->runMiddlewares($middlewares, $request);
                $route["controller"]->handle($request);
            }
        }

        throw new HTTPNotFoundException();
    }

    private function runMiddlewares(array $middlewares, Request $request): void
    {
        foreach ($middlewares as $middleware) {
            Middleware::resolve($middleware);
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
