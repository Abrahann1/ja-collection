<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Session;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Services\ProductService;

class ProductController
{
    private ProductService $productService;
    private BrandRepository $brandRepo;
    private CategoryRepository $categoryRepo;

    public function __construct()
    {
        $this->productService = new ProductService();
        $this->brandRepo = new BrandRepository();
        $this->categoryRepo = new CategoryRepository();
    }

    public function index(Request $request): void
    {
        $filters = [
            'search' => $request->query('q'),
            'brand' => $request->query('brand'),
            'category' => $request->query('category'),
            'sort' => $request->query('sort', 'newest')
        ];

        $page = max(1, (int)$request->query('page', 1));
        $data = $this->productService->listAdminProducts($filters, $page, 15);
        $brands = $this->brandRepo->getAllActive();
        $categories = $this->categoryRepo->getAllActive();

        View::render('admin.products.index', [
            'title' => 'Gestión de Productos | J.A ADMIN',
            'products' => $data['items'],
            'pagination' => $data['pagination'],
            'brands' => $brands,
            'categories' => $categories,
            'filters' => $filters,
            'success' => Session::get('flash_success')
        ], 'admin');

        Session::remove('flash_success');
    }

    public function create(Request $request): void
    {
        $brands = $this->brandRepo->getAllActive();
        $categories = $this->categoryRepo->getAllActive();

        View::render('admin.products.form', [
            'title' => 'Nuevo Modelo Diecast | J.A ADMIN',
            'isEdit' => false,
            'product' => [],
            'brands' => $brands,
            'categories' => $categories
        ], 'admin');
    }

    public function store(Request $request): void
    {
        $user = Session::get('user');
        $userId = $user ? (int)$user['id'] : null;

        $result = $this->productService->createProduct($request->body(), $userId);

        if (!$result['success']) {
            $brands = $this->brandRepo->getAllActive();
            $categories = $this->categoryRepo->getAllActive();

            View::render('admin.products.form', [
                'title' => 'Nuevo Modelo Diecast | J.A ADMIN',
                'isEdit' => false,
                'product' => $request->body(),
                'errors' => $result['errors'],
                'brands' => $brands,
                'categories' => $categories
            ], 'admin');
            return;
        }

        Session::set('flash_success', 'Modelo creado e inventariado con éxito.');
        Response::redirect('/admin/products');
    }

    public function edit(Request $request, string $id): void
    {
        $product = $this->productService->getProductDetail((int)$id);
        if (!$product) {
            Response::redirect('/admin/products');
        }

        $brands = $this->brandRepo->getAllActive();
        $categories = $this->categoryRepo->getAllActive();

        View::render('admin.products.form', [
            'title' => 'Editar Modelo: ' . htmlspecialchars($product['name']),
            'isEdit' => true,
            'product' => $product,
            'brands' => $brands,
            'categories' => $categories
        ], 'admin');
    }

    public function update(Request $request, string $id): void
    {
        $result = $this->productService->updateProduct((int)$id, $request->body());

        if (!$result['success']) {
            $brands = $this->brandRepo->getAllActive();
            $categories = $this->categoryRepo->getAllActive();

            View::render('admin.products.form', [
                'title' => 'Editar Modelo | J.A ADMIN',
                'isEdit' => true,
                'product' => array_merge($request->body(), ['id' => $id]),
                'errors' => $result['errors'],
                'brands' => $brands,
                'categories' => $categories
            ], 'admin');
            return;
        }

        Session::set('flash_success', 'Modelo actualizado con éxito.');
        Response::redirect('/admin/products');
    }

    public function duplicate(Request $request, string $id): void
    {
        $user = Session::get('user');
        $userId = $user ? (int)$user['id'] : null;

        $result = $this->productService->duplicateProduct((int)$id, $userId);

        if ($result['success']) {
            Session::set('flash_success', 'Modelo duplicado con éxito. Puedes editar su nuevo SKU y color.');
        }

        Response::redirect('/admin/products');
    }

    public function delete(Request $request, string $id): void
    {
        $result = $this->productService->deleteProduct((int)$id);
        if ($result['success']) {
            Session::set('flash_success', 'Producto archivado del catálogo.');
        }
        Response::redirect('/admin/products');
    }
}