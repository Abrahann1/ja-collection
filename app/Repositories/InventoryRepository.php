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

    public function getByProductId(int $productId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM inventory WHERE product_id = :pid LIMIT 1");
        $stmt->execute(['pid' => $productId]);
        $result = $stmt->fetch();
        return $result ?: null;
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