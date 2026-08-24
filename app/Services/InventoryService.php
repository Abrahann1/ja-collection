<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Repositories\InventoryRepository;
use Exception;

class InventoryService
{
    private InventoryRepository $inventoryRepo;

    public function __construct()
    {
        $this->inventoryRepo = new InventoryRepository();
    }

    public function listAll(): array
    {
        return $this->inventoryRepo->getAllInventoryList();
    }

    public function listRecentMovements(): array
    {
        return $this->inventoryRepo->getRecentMovements(20);
    }

    public function adjustStock(int $productId, int $quantityChange, string $type, string $reason, ?int $userId = null): array
    {
        $db = Database::getConnection();
        try {
            $db->beginTransaction();

            $stmt = $db->prepare("SELECT stock_current, minimum_stock FROM inventory WHERE product_id = :pid FOR UPDATE");
            $stmt->execute(['pid' => $productId]);
            $inv = $stmt->fetch();

            if (!$inv) {
                $db->rollBack();
                return ['success' => false, 'message' => 'El producto no tiene registro de inventario.'];
            }

            $current = (int)$inv['stock_current'];
            $min = (int)$inv['minimum_stock'];
            $newStock = $current + $quantityChange;

            if ($newStock < 0) {
                $db->rollBack();
                return ['success' => false, 'message' => 'No hay suficiente stock para realizar este descuento.'];
            }

            $status = $newStock > $min ? 'DISPONIBLE' : ($newStock > 0 ? 'STOCK_BAJO' : 'AGOTADO');

            $stmtUp = $db->prepare("UPDATE inventory SET stock_current = :curr, status = :status WHERE product_id = :pid");
            $stmtUp->execute(['curr' => $newStock, 'status' => $status, 'pid' => $productId]);

            $this->inventoryRepo->logMovement($productId, $type, $quantityChange, $current, $newStock, $reason, $userId);

            $db->commit();
            return ['success' => true, 'message' => 'Stock actualizado y registrado en el Kardex.'];

        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Error al ajustar inventario: ' . $e->getMessage()];
        }
    }
}