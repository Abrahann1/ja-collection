<?php
declare(strict_types=1);

namespace App\Models;

class Inventory
{
    public function __construct(
        public ?int $id = null,
        public int $productId = 0,
        public int $stockCurrent = 0,
        public int $stockReserved = 0,
        public int $stockAvailable = 0,
        public int $minimumStock = 2,
        public string $status = 'AGOTADO'
    ) {}
}