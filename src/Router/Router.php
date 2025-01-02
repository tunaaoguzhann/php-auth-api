<?php

namespace Tunaaoguzhann\PhpAuthApi\Router;

use Tunaaoguzhann\PhpAuthApi\Controllers\UserController;
use Tunaaoguzhann\PhpAuthApi\Http\Request;
use Tunaaoguzhann\PhpAuthApi\Http\Response;

class Router
{
    private array $routes = [
        '/api/auth/login' => [
            'method' => ['POST'],
            'handler' => [UserController::class, 'login']
        ],
        '/api/auth/register' => [
            'method' => ['POST'],
            'handler' => [UserController::class, 'register']
        ],
        '/api/user/profile' => [
            'method' => ['GET'],
            'handler' => [UserController::class, 'getProfile']
        ]
    ];

    public function run(): void
    {
        $request = Request::createFromGlobals();

        error_log('Request Path: ' . $request->getPath());
        error_log('Request Method: ' . $request->getMethod());

        if ($request->isMethod('OPTIONS')) {
            Response::success()->withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
            ])->send();
            exit();
        }

        try {
            $path = $request->getPath();
            
            // Debug için
            error_log('Available Routes: ' . print_r(array_keys($this->routes), true));
            
            if (!isset($this->routes[$path])) {
                Response::notFound('Route not found')->send();
                exit();
            }

            $route = $this->routes[$path];
            if (!in_array($request->getMethod(), $route['method'])) {
                Response::methodNotAllowed()->send();
                exit();
            }

            [$class, $function] = $route['handler'];
            $response = $class::$function($request);
            $response->send();

        } catch (\Exception $e) {
            error_log('Error: ' . $e->getMessage());
            Response::serverError($e->getMessage())->send();
        }
    }

    public function addRoute(string $path, array $methods, array $handler): void
    {
        $this->routes[$path] = [
            'method' => $methods,
            'handler' => $handler
        ];
    }
} 