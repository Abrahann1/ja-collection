<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Repositories\CategoryRepository;
use App\Repositories\BrandRepository;
use App\Services\ProductService;

class HomeController
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
        $featured = $this->productService->listCatalog(['featured' => 1], 1, 6);
        $recent = $this->productService->listCatalog(['new' => 1], 1, 6);
        $categories = $this->categoryRepo->getAllActive();
        $brands = $this->brandRepo->getAllActive();

        View::render('pages.home', [
            'title' => 'J.A COLLECTION | Luxury Automotive Diecast Collectibles',
            'featuredProducts' => $featured['items'],
            'recentProducts' => $recent['items'],
            'categories' => $categories,
            'brands' => $brands
        ]);
    }
}