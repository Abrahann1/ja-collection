<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\InventoryRepository;
use Exception;

class OrderService
{
    private OrderRepository $orderRepo;
    private ProductRepository $productRepo;
    private InventoryRepository $inventoryRepo;

    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
        $this->productRepo = new ProductRepository();
        $this->inventoryRepo = new InventoryRepository();
    }

    public function processCheckout(array $data, array $cartItems, ?int $userId = null): array
    {
        if (empty($cartItems)) {
            return ['success' => false, 'message' => 'La bolsa de compras está vacía.'];
        }

        if (empty($data['customer_name']) || empty($data['customer_email']) || empty($data['customer_phone']) || empty($data['shipping_address'])) {
            return ['success' => false, 'message' => 'Por favor completa todos los campos de entrega obligatorios.'];
        }

        $db = Database::getConnection();
        try {
            $db->beginTransaction();

            $subtotal = 0.0;
            $itemsToProcess = [];

            // 1. Validar precio y stock de cada modelo directo desde MySQL
            foreach ($cartItems as $item) {
                $productId = (int)($item['id'] ?? 0);
                $qty = max(1, (int)($item['quantity'] ?? 1));

                $stmt = $db->prepare("SELECT p.id, p.name, p.sku, p.price, i.stock_current, i.minimum_stock FROM products p JOIN inventory i ON p.id = i.product_id WHERE p.id = :pid AND p.status = 'ACTIVE' FOR UPDATE");
                $stmt->execute(['pid' => $productId]);
                $prod = $stmt->fetch();

                if (!$prod) {
                    $db->rollBack();
                    return ['success' => false, 'message' => "El modelo '{$item['name']}' ya no está disponible en catálogo."];
                }

                $availableStock = (int)$prod['stock_current'];
                if ($availableStock < $qty) {
                    $db->rollBack();
                    return ['success' => false, 'message' => "Stock insuficiente para '{$prod['name']}'. Disponibles: {$availableStock} unidades."];
                }

                $realPrice = (float)$prod['price'];
                $itemSubtotal = $realPrice * $qty;
                $subtotal += $itemSubtotal;

                $itemsToProcess[] = [
                    'product_id' => $productId,
                    'name' => $prod['name'],
                    'sku' => $prod['sku'],
                    'price' => $realPrice,
                    'quantity' => $qty,
                    'subtotal' => $itemSubtotal,
                    'stock_current' => $availableStock,
                    'minimum_stock' => (int)$prod['minimum_stock']
                ];
            }

            // 2. Calcular Flete y Total
            $shippingCost = (float)($data['shipping_cost'] ?? 15.00);
            $total = $subtotal + $shippingCost;
            $orderNumber = 'JA-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            // 3. Insertar Pedido
            $stmtOrder = $db->prepare("INSERT INTO orders (order_number, user_id, customer_name, customer_email, customer_phone, shipping_department, shipping_province, shipping_district, shipping_address, subtotal, shipping_cost, total, payment_method, status, notes) VALUES (:onum, :uid, :name, :email, :phone, :dept, :prov, :dist, :addr, :sub, :ship, :tot, :pmethod, 'PENDING', :notes)");
            $stmtOrder->execute([
                'onum' => $orderNumber,
                'uid' => $userId,
                'name' => trim($data['customer_name']),
                'email' => strtolower(trim($data['customer_email'])),
                'phone' => trim($data['customer_phone']),
                'dept' => trim($data['shipping_department'] ?? 'Lima'),
                'prov' => trim($data['shipping_province'] ?? 'Lima'),
                'dist' => trim($data['shipping_district'] ?? 'Miraflores'),
                'addr' => trim($data['shipping_address']),
                'sub' => $subtotal,
                'ship' => $shippingCost,
                'tot' => $total,
                'pmethod' => trim($data['payment_method'] ?? 'YAPE_PLIN'),
                'notes' => trim($data['notes'] ?? '')
            ]);

            $orderId = (int)$db->lastInsertId();

            // 4. Insertar Líneas y Descontar Inventario con Kardex
            $stmtLine = $db->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_sku, price, quantity, subtotal) VALUES (:oid, :pid, :pname, :psku, :price, :qty, :sub)");
            $stmtInvUp = $db->prepare("UPDATE inventory SET stock_current = :new_stock, status = :status WHERE product_id = :pid");

            foreach ($itemsToProcess as $p) {
                $stmtLine->execute([
                    'oid' => $orderId,
                    'pid' => $p['product_id'],
                    'pname' => $p['name'],
                    'psku' => $p['sku'],
                    'price' => $p['price'],
                    'qty' => $p['quantity'],
                    'sub' => $p['subtotal']
                ]);

                $newStock = $p['stock_current'] - $p['quantity'];
                $newStatus = $newStock > $p['minimum_stock'] ? 'DISPONIBLE' : ($newStock > 0 ? 'STOCK_BAJO' : 'AGOTADO');
                $stmtInvUp->execute([
                    'new_stock' => $newStock,
                    'status' => $newStatus,
                    'pid' => $p['product_id']
                ]);

                $this->inventoryRepo->logMovement($p['product_id'], 'SALE', -$p['quantity'], $p['stock_current'], $newStock, "Venta Pedido #{$orderNumber}", $userId);
            }

            $db->commit();
            return ['success' => true, 'order_number' => $orderNumber, 'order_id' => $orderId];

        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Error al procesar el pedido: ' . $e->getMessage()];
        }
    }

    public function getOrderDetails(string $orderNumber): mixed
    {
        return $this->orderRepo->findByOrderNumber($orderNumber);
    }

    public function listOrders(string $status = ''): array
    {
        return $this->orderRepo->getAll($status);
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        $paymentStatus = in_array($status, ['PAID', 'PROCESSING', 'SHIPPED', 'DELIVERED'], true) ? 'PAID' : 'PENDING';
        return $this->orderRepo->updateStatus($orderId, $status, $paymentStatus);
    }
}