<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class InventoryRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllInventoryList(): array
    {
        $sql = "SELECT p.id AS product_id, p.name, p.sku, p.scale, 
                       b.name AS brand_name, c.name AS category_name,
                       COALESCE(i.stock_current, 0) AS stock_current,
                       COALESCE(i.stock_reserved, 0) AS stock_reserved,
                       COALESCE(i.stock_available, 0) AS stock_available,
                       COALESCE(i.minimum_stock, 2) AS minimum_stock,
                       COALESCE(i.status, 'AGOTADO') AS stock_status,
                       i.updated_at
                FROM products p
                JOIN brands b ON p.brand_id = b.id
                JOIN categories c ON p.category_id = c.id
                LEFT JOIN inventory i ON p.id = i.product_id
                WHERE p.status != 'ARCHIVED'
                ORDER BY i.stock_available ASC, p.name ASC";

        return $this->db->query($sql)->fetchAll();
    }

    public function getMovementsByProduct(int $productId, int $limit = 10): array
    {
        $stmt = $this->db->prepare("SELECT m.*, u.name AS user_name FROM inventory_movements m LEFT JOIN users u ON m.user_id = u.id WHERE m.product_id = :pid ORDER BY m.id DESC LIMIT :limit");
        $stmt->bindValue(':pid', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRecentMovements(int $limit = 20): array
    {
        $stmt = $this->db->prepare("SELECT m.*, p.name AS product_name, p.sku, u.name AS user_name FROM inventory_movements m JOIN products p ON m.product_id = p.id LEFT JOIN users u ON m.user_id = u.id ORDER BY m.id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function logMovement(int $productId, string $type, int $quantity, int $stockBefore, int $stockAfter, string $reason, ?int $userId = null): bool
    {
        $stmt = $this->db->prepare("INSERT INTO inventory_movements (product_id, type, quantity, stock_before, stock_after, reason, user_id) VALUES (:pid, :type, :qty, :before, :after, :reason, :uid)");
        return $stmt->execute([
            'pid' => $productId,
            'type' => $type,
            'qty' => $quantity,
            'before' => $stockBefore,
            'after' => $stockAfter,
            'reason' => $reason,
            'uid' => $userId
        ]);
    }
}