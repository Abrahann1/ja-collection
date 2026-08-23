<?php
declare(strict_types=1);

namespace App\Models;

class Category
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public string $slug = '',
        public ?string $description = null,
        public ?string $imageUrl = null,
        public string $status = 'ACTIVE'
    ) {}
}