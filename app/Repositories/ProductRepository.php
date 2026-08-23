<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ProductRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getPaginated(array $filters = [], int $page = 1, int $perPage = 12, bool $onlyActive = true): array
    {
        $offset = ($page - 1) * $perPage;
        $conditions = [];
        $params = [];

        if ($onlyActive) {
            $conditions[] = "p.status = 'ACTIVE'";
        } else {
            $conditions[] = "p.status != 'ARCHIVED'";
        }

        if (!empty($filters['brand'])) {
            $conditions[] = "b.slug = :brand";
            $params['brand'] = $filters['brand'];
        }

        if (!empty($filters['category'])) {
            $conditions[] = "c.slug = :category";
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['scale'])) {
            $conditions[] = "p.scale = :scale";
            $params['scale'] = $filters['scale'];
        }

        if (!empty($filters['search'])) {
            $conditions[] = "(p.name LIKE :search OR p.sku LIKE :search2 OR p.model LIKE :search3)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params['search'] = $searchTerm;
            $params['search2'] = $searchTerm;
            $params['search3'] = $searchTerm;
        }

        $whereClause = !empty($conditions) ? implode(' AND ', $conditions) : '1=1';

        $orderBy = match ($filters['sort'] ?? 'newest') {
            'price_asc' => 'p.price ASC',
            'price_desc' => 'p.price DESC',
            'name_asc' => 'p.name ASC',
            default => 'p.id DESC',
        };

        $countSql = "SELECT COUNT(*) FROM products p 
                     JOIN brands b ON p.brand_id = b.id 
                     JOIN categories c ON p.category_id = c.id 
                     WHERE {$whereClause}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $totalItems = (int)$countStmt->fetchColumn();

        $sql = "SELECT p.*, 
                       b.name AS brand_name, b.slug AS brand_slug,
                       c.name AS category_name, c.slug AS category_slug,
                       COALESCE(i.stock_current, 0) AS stock_current,
                       COALESCE(i.stock_available, 0) AS stock_available,
                       COALESCE(i.minimum_stock, 2) AS minimum_stock,
                       COALESCE(i.status, 'AGOTADO') AS stock_status
                FROM products p
                JOIN brands b ON p.brand_id = b.id
                JOIN categories c ON p.category_id = c.id
                LEFT JOIN inventory i ON p.id = i.product_id
                WHERE {$whereClause}
                ORDER BY {$orderBy}
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue(":{$key}", $val);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'items' => $stmt->fetchAll(),
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total_items' => $totalItems,
                'total_pages' => (int)ceil($totalItems / $perPage)
            ]
        ];
    }

    public function findByIdOrSlug(mixed $identifier): mixed
    {
        $field = is_numeric($identifier) ? 'p.id' : (str_starts_with((string)$identifier, 'HW-') || str_starts_with((string)$identifier, 'MGT-') || str_starts_with((string)$identifier, 'MBX-') ? 'p.sku' : 'p.slug');
        
        $sql = "SELECT p.*, 
                       b.name AS brand_name, b.slug AS brand_slug,
                       c.name AS category_name, c.slug AS category_slug,
                       COALESCE(i.stock_current, 0) AS stock_current,
                       COALESCE(i.stock_available, 0) AS stock_available,
                       COALESCE(i.minimum_stock, 2) AS minimum_stock,
                       COALESCE(i.status, 'AGOTADO') AS stock_status
                FROM products p
                JOIN brands b ON p.brand_id = b.id
                JOIN categories c ON p.category_id = c.id
                LEFT JOIN inventory i ON p.id = i.product_id
                WHERE {$field} = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $identifier]);
        $product = $stmt->fetch();

        return $product ?: null;
    }

    public function isSkuUnique(string $sku, mixed $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM products WHERE sku = :sku";
        $params = ['sku' => trim($sku)];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = (int)$excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() === 0;
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO products (sku, name, slug, brand_id, category_id, scale, model, description, price, old_price, is_featured, is_new, status) 
                VALUES (:sku, :name, :slug, :brand_id, :category_id, :scale, :model, :description, :price, :old_price, :is_featured, :is_new, :status)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'sku' => strtoupper(trim($data['sku'])),
            'name' => trim($data['name']),
            'slug' => $data['slug'],
            'brand_id' => (int)$data['brand_id'],
            'category_id' => (int)$data['category_id'],
            'scale' => trim($data['scale'] ?? '1:64'),
            'model' => trim($data['model'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'price' => (float)$data['price'],
            'old_price' => !empty($data['old_price']) ? (float)$data['old_price'] : null,
            'is_featured' => !empty($data['is_featured']) ? 1 : 0,
            'is_new' => !empty($data['is_new']) ? 1 : 0,
            'status' => $data['status'] ?? 'ACTIVE'
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE products SET 
                sku = :sku,
                name = :name,
                slug = :slug,
                brand_id = :brand_id,
                category_id = :category_id,
                scale = :scale,
                model = :model,
                description = :description,
                price = :price,
                old_price = :old_price,
                is_featured = :is_featured,
                is_new = :is_new,
                status = :status
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'sku' => strtoupper(trim($data['sku'])),
            'name' => trim($data['name']),
            'slug' => $data['slug'],
            'brand_id' => (int)$data['brand_id'],
            'category_id' => (int)$data['category_id'],
            'scale' => trim($data['scale'] ?? '1:64'),
            'model' => trim($data['model'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'price' => (float)$data['price'],
            'old_price' => !empty($data['old_price']) ? (float)$data['old_price'] : null,
            'is_featured' => !empty($data['is_featured']) ? 1 : 0,
            'is_new' => !empty($data['is_new']) ? 1 : 0,
            'status' => $data['status'] ?? 'ACTIVE'
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE products SET status = 'ARCHIVED' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}