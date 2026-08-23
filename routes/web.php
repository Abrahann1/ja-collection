<?php
declare(strict_types=1);

use App\Core\Request;
use App\Core\View;
use App\Controllers\HomeController;
use App\Controllers\ShopController;
use App\Controllers\ProductController as PublicProductController;
use App\Controllers\AuthController;
use App\Controllers\Admin\ProductController as AdminProductController;
use App\Middleware\AdminMiddleware;

/** @var App\Core\Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('/shop', [ShopController::class, 'index']);
$router->get('/product', [PublicProductController::class, 'show']);
$router->get('/product/{id}', [PublicProductController::class, 'show']);

$router->get('/cart', function (Request $request) {
    View::render('pages.cart', ['title' => 'Bolsa de Compras | J.A COLLECTION']);
});

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'handleLogin']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'handleRegister']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/admin/login', [AuthController::class, 'showAdminLogin']);
$router->post('/admin/login', [AuthController::class, 'handleLogin']);
$router->get('/admin/logout', [AuthController::class, 'adminLogout']);

$router->get('/admin/dashboard', function (Request $request) {
    View::render('admin.dashboard', ['title' => 'Dashboard Administrativo | J.A COLLECTION'], 'admin');
}, [AdminMiddleware::class]);

$router->get('/admin/products', [AdminProductController::class, 'index'], [AdminMiddleware::class]);
$router->get('/admin/products/create', [AdminProductController::class, 'create'], [AdminMiddleware::class]);
$router->post('/admin/products/create', [AdminProductController::class, 'store'], [AdminMiddleware::class]);
$router->get('/admin/products/{id}/edit', [AdminProductController::class, 'edit'], [AdminMiddleware::class]);
$router->post('/admin/products/{id}/edit', [AdminProductController::class, 'update'], [AdminMiddleware::class]);
$router->get('/admin/products/{id}/duplicate', [AdminProductController::class, 'duplicate'], [AdminMiddleware::class]);
$router->get('/admin/products/{id}/delete', [AdminProductController::class, 'delete'], [AdminMiddleware::class]);