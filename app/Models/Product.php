<?php
declare(strict_types=1);

namespace App\Models;

class Product
{
    public function __construct(
        public ?int $id = null,
        public string $sku = '',
        public string $name = '',
        public string $slug = '',
        public int $brandId = 0,
        public int $categoryId = 0,
        public string $scale = '1:64',
        public ?string $model = null,
        public ?string $description = null,
        public float $price = 0.0,
        public ?float $oldPrice = null,
        public bool $isFeatured = false,
        public bool $isNew = false,
        public string $status = 'ACTIVE',
        public ?string $brandName = null,
        public ?string $categoryName = null,
        public int $stock = 0
    ) {}
}