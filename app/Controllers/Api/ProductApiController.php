<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Services\ProductService;

class ProductApiController
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function index(Request $request): void
    {
        $filters = [
            'brand' => $request->query('brand'),
            'category' => $request->query('category'),
            'scale' => $request->query('scale'),
            'featured' => $request->query('featured'),
            'new' => $request->query('new'),
            'search' => $request->query('q'),
            'sort' => $request->query('sort', 'newest')
        ];

        $page = max(1, (int)$request->query('page', 1));
        $perPage = min(50, max(1, (int)$request->query('per_page', 12)));

        $result = $this->productService->listCatalog($filters, $page, $perPage);

        Response::json([
            'success' => true,
            'data' => $result['items'],
            'pagination' => $result['pagination']
        ]);
    }

    public function show(Request $request, string $identifier): void
    {
        $product = $this->productService->getProductDetail($identifier);

        if (!$product) {
            Response::json([
                'success' => false,
                'message' => 'El producto solicitado no existe o no se encuentra disponible.'
            ], 404);
            return;
        }

        Response::json([
            'success' => true,
            'data' => $product
        ]);
    }
}