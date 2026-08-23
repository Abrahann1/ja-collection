<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Repositories\CategoryRepository;
use App\Repositories\BrandRepository;
use App\Services\ProductService;

class ShopController
{
    private ProductService $productService;
    private CategoryRepository $categoryRepo;
    private BrandRepository $brandRepo;

    public function __construct()
    {
        $this->productService = new ProductService();
        $this->categoryRepo = new CategoryRepository();
        $this->brandRepo = new BrandRepository();
    }

    public function index(Request $request): void
    {
        $filters = [
            'brand' => $request->query('brand'),
            'category' => $request->query('category'),
            'scale' => $request->query('scale'),
            'search' => $request->query('q'),
            'sort' => $request->query('sort', 'newest')
        ];

        $page = max(1, (int)$request->query('page', 1));
        $data = $this->productService->listCatalog($filters, $page, 12);
        $categories = $this->categoryRepo->getAllActive();
        $brands = $this->brandRepo->getAllActive();

        View::render('pages.shop', [
            'title' => 'Catálogo Exclusivo | J.A COLLECTION',
            'extraCss' => 'shop',
            'products' => $data['items'],
            'pagination' => $data['pagination'],
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $filters
        ]);
    }
}