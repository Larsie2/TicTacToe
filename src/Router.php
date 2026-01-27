<?php
namespace src;

use src\Controllers\Controller;

final class Router {
    private array $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function get(string $path, string $action) : void {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, string $action) : void {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch() : void {
        $uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        $action = $this->routes[$method][$uri] ?? null;

        if ($action === null) {
            http_response_code(404);
            echo '404 - Page not found';
            return;
        }

        $controller = new Controller();
        $controller->$action();
    }
}