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

        // Obtener modelos relacionados para la sección de recomendaciones
        $relatedData = $this->productService->listCatalog([
            'category' => $product['category_slug'] ?? ''
        ], 1, 8);

        $relatedProducts = array_filter($relatedData['items'], function ($item) use ($product) {
            return (int)$item['id'] !== (int)$product['id'];
        });
        $relatedProducts = array_slice($relatedProducts, 0, 4);

        if (count($relatedProducts) < 4) {
            $featuredData = $this->productService->listCatalog(['featured' => 1], 1, 8);
            foreach ($featuredData['items'] as $item) {
                if ((int)$item['id'] !== (int)$product['id'] && !in_array($item['id'], array_column($relatedProducts, 'id'), true)) {
                    $relatedProducts[] = $item;
                    if (count($relatedProducts) >= 4) {
                        break;
                    }
                }
            }
        }

        View::render('pages.product', [
            'title' => $product['name'] . ' (Escala ' . $product['scale'] . ') | J.A COLLECTION',
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
}