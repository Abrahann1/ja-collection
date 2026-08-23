<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\ProductService;

class ProductController
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function show(Request $request, mixed $identifier = null): void
    {
        $target = $identifier ?? $request->query('sku') ?? $request->query('slug') ?? $request->query('id');

        if (!$target) {
            Response::redirect('/shop');
        }

        $product = $this->productService->getProductDetail($target);

        if (!$product) {
            Response::html('<h1>Modelo no disponible en el catálogo de J.A COLLECTION.</h1>', 404);
            return;
        }

        View::render('pages.product', [
            'title' => $product['name'] . ' (Escala ' . $product['scale'] . ') | J.A COLLECTION',
            'extraCss' => 'product',
            'product' => $product
        ]);
    }
}