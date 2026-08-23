<?php
declare(strict_types=1);

namespace App\Models;

class Brand
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public string $slug = '',
        public ?string $description = null,
        public ?string $logoUrl = null,
        public string $status = 'ACTIVE'
    ) {}
}