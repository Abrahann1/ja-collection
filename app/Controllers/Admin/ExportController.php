<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Core\Database;
use PDO;

class ExportController
{
    public function index(Request $request): void
    {
        $db = Database::getConnection();

        // Métricas de valorización de inventario
        $stmtVal = $db->query("SELECT SUM(p.price * i.stock_current) AS total_inventory_value, SUM(i.stock_current) AS total_units FROM products p JOIN inventory i ON p.id = i.product_id WHERE p.status != 'ARCHIVED'");
        $valData = $stmtVal->fetch();

        View::render('admin.export.index', [
            'title' => 'Exportación Contable & Reportes | J.A ADMIN',
            'inventoryValue' => (float)($valData['total_inventory_value'] ?? 0),
            'totalUnits' => (int)($valData['total_units'] ?? 0)
        ], 'admin');
    }

    public function exportProducts(Request $request): void
    {
        $db = Database::getConnection();
        $sql = "SELECT p.sku, p.name, b.name AS brand, c.name AS category, p.scale, p.price, p.old_price, COALESCE(i.stock_current, 0) AS stock, p.status, p.created_at FROM products p JOIN brands b ON p.brand_id = b.id JOIN categories c ON p.category_id = c.id LEFT JOIN inventory i ON p.id = i.product_id WHERE p.status != 'ARCHIVED' ORDER BY p.id DESC";
        $products = $db->query($sql)->fetchAll();

        $this->downloadCsv("catalogo_productos_ja_collection_" . date('Ymd') . ".csv", [
            'SKU', 'Nombre Modelo', 'Marca', 'Categoría', 'Escala', 'Precio (S/)', 'Precio Anterior', 'Stock Físico', 'Estado', 'Fecha Registro'
        ], $products);
    }

    public function exportInventory(Request $request): void
    {
        $db = Database::getConnection();
        $sql = "SELECT p.sku, p.name, b.name AS brand, p.scale, p.price, i.stock_current, i.stock_reserved, i.stock_available, (p.price * i.stock_current) AS total_value, i.status FROM products p JOIN brands b ON p.brand_id = b.id JOIN inventory i ON p.id = i.product_id WHERE p.status != 'ARCHIVED' ORDER BY i.stock_current DESC";
        $inventory = $db->query($sql)->fetchAll();

        $this->downloadCsv("kardex_inventario_ja_collection_" . date('Ymd') . ".csv", [
            'SKU', 'Nombre Modelo', 'Marca', 'Escala', 'Precio Unit (S/)', 'Stock Físico', 'Stock Reservado', 'Stock Disponible', 'Valorización (S/)', 'Estado Stock'
        ], $inventory);
    }

    public function exportOrders(Request $request): void
    {
        $db = Database::getConnection();
        $sql = "SELECT order_number, customer_name, customer_email, customer_phone, shipping_department, shipping_district, subtotal, shipping_cost, total, payment_method, status, created_at FROM orders ORDER BY id DESC";
        $orders = $db->query($sql)->fetchAll();

        $this->downloadCsv("reporte_ventas_pedidos_ja_collection_" . date('Ymd') . ".csv", [
            'N° Pedido', 'Cliente', 'Email', 'Teléfono', 'Departamento', 'Distrito', 'Subtotal (S/)', 'Envío (S/)', 'Total (S/)', 'Método Pago', 'Estado', 'Fecha'
        ], $orders);
    }

    private function downloadCsv(string $filename, array $headers, array $rows): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        // BOM UTF-8 para que Excel abra tildes sin problemas
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($out, $headers);
        foreach ($rows as $r) {
            fputcsv($out, array_values($r));
        }
        fclose($out);
        exit;
    }
}