<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

class DashboardService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getDashboardMetrics(): array
    {
        // 1. Ventas Totales (Pedidos no cancelados)
        $stmtSales = $this->db->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'CANCELLED'");
        $totalSales = (float)$stmtSales->fetchColumn();

        // 2. Pedidos Activos
        $stmtOrders = $this->db->query("SELECT COUNT(*) FROM orders WHERE status IN ('PENDING', 'PAID', 'PROCESSING', 'SHIPPED')");
        $activeOrders = (int)$stmtOrders->fetchColumn();

        // 3. Modelos Activos en Catálogo
        $stmtProd = $this->db->query("SELECT COUNT(*) FROM products WHERE status = 'ACTIVE'");
        $catalogCount = (int)$stmtProd->fetchColumn();

        // 4. Alertas de Stock Bajo / Agotado
        $stmtLowStock = $this->db->query("SELECT COUNT(*) FROM inventory WHERE status IN ('STOCK_BAJO', 'AGOTADO')");
        $lowStockCount = (int)$stmtLowStock->fetchColumn();

        // 5. Últimos Pedidos Reales
        $stmtRecent = $this->db->query("SELECT id, order_number, customer_name, total, status, created_at FROM orders ORDER BY id DESC LIMIT 7");
        $recentOrders = $stmtRecent->fetchAll();

        return [
            'total_sales' => $totalSales,
            'total_sales_formatted' => 'S/ ' . number_format($totalSales, 2),
            'active_orders' => $activeOrders,
            'catalog_count' => $catalogCount,
            'low_stock_count' => $lowStockCount,
            'recent_orders' => $recentOrders
        ];
    }
}