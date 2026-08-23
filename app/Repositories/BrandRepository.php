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
        $stmt = $this->db->query("SELECT id, name, slug, description, logo_url FROM brands WHERE status = 'ACTIVE' ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM brands WHERE slug = :slug AND status = 'ACTIVE' LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}