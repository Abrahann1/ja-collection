<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class OrderRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(string $statusFilter = ''): array
    {
        $sql = "SELECT * FROM orders";
        $params = [];

        if (!empty($statusFilter)) {
            $sql .= " WHERE status = :status";
            $params['status'] = $statusFilter;
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): mixed
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();

        if (!$order) return null;

        $stmtItems = $this->db->prepare("SELECT * FROM order_items WHERE order_id = :oid");
        $stmtItems->execute(['oid' => $id]);
        $order['items'] = $stmtItems->fetchAll();

        return $order;
    }

    public function findByOrderNumber(string $orderNumber): mixed
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE order_number = :num LIMIT 1");
        $stmt->execute(['num' => $orderNumber]);
        $order = $stmt->fetch();

        if (!$order) return null;

        $stmtItems = $this->db->prepare("SELECT * FROM order_items WHERE order_id = :oid");
        $stmtItems->execute(['oid' => $order['id']]);
        $order['items'] = $stmtItems->fetchAll();

        return $order;
    }

    public function updateStatus(int $id, string $status, string $paymentStatus = 'PENDING'): bool
    {
        $stmt = $this->db->prepare("UPDATE orders SET status = :status, payment_status = :pstatus WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'pstatus' => $paymentStatus
        ]);
    }
}