<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class BrandRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllActive(): array
    {
        $stmt = $this->db->query("SELECT id, name, slug, description, logo_url, status FROM brands WHERE status = 'ACTIVE' ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getAllAdmin(): array
    {
        $stmt = $this->db->query("SELECT b.*, COUNT(p.id) AS products_count FROM brands b LEFT JOIN products p ON b.id = p.brand_id AND p.status != 'ARCHIVED' GROUP BY b.id ORDER BY b.name ASC");
        return $stmt->fetchAll();
    }

    public function findById(int $id): mixed
    {
        $stmt = $this->db->prepare("SELECT * FROM brands WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function create(string $name, string $description = ''): bool
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $stmt = $this->db->prepare("INSERT INTO brands (name, slug, description, status) VALUES (:name, :slug, :desc, 'ACTIVE')");
        return $stmt->execute(['name' => trim($name), 'slug' => $slug, 'desc' => trim($description)]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE brands SET status = 'INACTIVE' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}