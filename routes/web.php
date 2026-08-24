<?php
declare(strict_types=1);

use App\Core\Request;
use App\Core\View;
use App\Controllers\HomeController;
use App\Controllers\ShopController;
use App\Controllers\ProductController as PublicProductController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\AuthController;
use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\ProductController as AdminProductController;
use App\Controllers\Admin\BrandController as AdminBrandController;
use App\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Controllers\Admin\ImportController as AdminImportController;
use App\Controllers\Admin\OrderController as AdminOrderController;
use App\Middleware\AdminMiddleware;

/** @var App\Core\Router $router */

// Rutas Públicas
$router->get('/', [HomeController::class, 'index']);
$router->get('/shop', [ShopController::class, 'index']);
$router->get('/product', [PublicProductController::class, 'show']);
$router->get('/product/{id}', [PublicProductController::class, 'show']);
$router->get('/cart', [CartController::class, 'index']);

// Checkout y Confirmación de Pedido
$router->get('/checkout', [CheckoutController::class, 'index']);
$router->post('/checkout', [CheckoutController::class, 'process']);
$router->get('/orders/success', [CheckoutController::class, 'success']);

// Autenticación
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'handleLogin']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'handleRegister']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/admin/login', [AuthController::class, 'showAdminLogin']);
$router->post('/admin/login', [AuthController::class, 'handleLogin']);
$router->get('/admin/logout', [AuthController::class, 'adminLogout']);

// Dashboard Administrativo Dinámico
$router->get('/admin/dashboard', [AdminController::class, 'dashboard'], [AdminMiddleware::class]);
$router->get('/api/admin/dashboard/stats', [AdminController::class, 'liveStats'], [AdminMiddleware::class]);

// CRUD Productos
$router->get('/admin/products', [AdminProductController::class, 'index'], [AdminMiddleware::class]);
$router->get('/admin/products/create', [AdminProductController::class, 'create'], [AdminMiddleware::class]);
$router->post('/admin/products/create', [AdminProductController::class, 'store'], [AdminMiddleware::class]);
$router->get('/admin/products/{id}/edit', [AdminProductController::class, 'edit'], [AdminMiddleware::class]);
$router->post('/admin/products/{id}/edit', [AdminProductController::class, 'update'], [AdminMiddleware::class]);
$router->get('/admin/products/{id}/duplicate', [AdminProductController::class, 'duplicate'], [AdminMiddleware::class]);
$router->get('/admin/products/{id}/delete', [AdminProductController::class, 'delete'], [AdminMiddleware::class]);

// Marcas y Categorías
$router->get('/admin/brands', [AdminBrandController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/brands', [AdminBrandController::class, 'store'], [AdminMiddleware::class]);
$router->get('/admin/brands/{id}/delete', [AdminBrandController::class, 'delete'], [AdminMiddleware::class]);

$router->get('/admin/categories', [AdminCategoryController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/categories', [AdminCategoryController::class, 'store'], [AdminMiddleware::class]);
$router->get('/admin/categories/{id}/delete', [AdminCategoryController::class, 'delete'], [AdminMiddleware::class]);

// Inventario y Kardex
$router->get('/admin/inventory', [AdminInventoryController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/inventory/adjust', [AdminInventoryController::class, 'adjust'], [AdminMiddleware::class]);

// Importación Masiva Excel
$router->get('/admin/import', [AdminImportController::class, 'index'], [AdminMiddleware::class]);
$router->post('/admin/import', [AdminImportController::class, 'upload'], [AdminMiddleware::class]);
$router->post('/admin/import/confirm', [AdminImportController::class, 'confirm'], [AdminMiddleware::class]);
$router->get('/admin/import/cancel', [AdminImportController::class, 'cancel'], [AdminMiddleware::class]);
$router->get('/admin/import/template', [AdminImportController::class, 'downloadTemplate'], [AdminMiddleware::class]);

// Gestión de Pedidos Admin
$router->get('/admin/orders', [AdminOrderController::class, 'index'], [AdminMiddleware::class]);
$router->get('/admin/orders/{id}', [AdminOrderController::class, 'show'], [AdminMiddleware::class]);
$router->post('/admin/orders/{id}/status', [AdminOrderController::class, 'updateStatus'], [AdminMiddleware::class]);