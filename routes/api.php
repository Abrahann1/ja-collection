<?php
declare(strict_types=1);

use App\Core\Request;
use App\Core\Response;
use App\Controllers\Api\ProductApiController;
use App\Controllers\Api\AuthApiController;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;

/** @var App\Core\Router $router */

$router->get('/api/health', function (Request $request) {
    Response::json([
        'success' => true,
        'status' => 'online',
        'app' => 'J.A COLLECTION API'
    ]);
});

// Productos
$router->get('/api/products', [ProductApiController::class, 'index']);
$router->get('/api/products/{id}', [ProductApiController::class, 'show']);

// Autenticación API
$router->post('/api/auth/register', [AuthApiController::class, 'register']);
$router->post('/api/auth/login', [AuthApiController::class, 'login']);
$router->post('/api/auth/logout', [AuthApiController::class, 'logout']);
$router->get('/api/auth/me', [AuthApiController::class, 'me'], [AuthMiddleware::class]);