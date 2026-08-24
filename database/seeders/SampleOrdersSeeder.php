<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Database;
use PDO;

class SampleOrdersSeeder
{
    public static function run(): void
    {
        $db = Database::getConnection();

        // Si ya hay más de 3 pedidos, no duplicar
        $stmtCheck = $db->query("SELECT COUNT(*) FROM orders");
        if ((int)$stmtCheck->fetchColumn() >= 6) {
            return;
        }

        $samples = [
            [
                'order_number' => 'JA-2026-8941',
                'customer_name' => 'Carlos Mendoza',
                'customer_email' => 'carlos.mendoza@gmail.com',
                'customer_phone' => '+51 984 123 456',
                'shipping_department' => 'Cusco',
                'shipping_province' => 'Cusco',
                'shipping_district' => 'San Jerónimo',
                'shipping_address' => 'Av. de la Cultura 850',
                'subtotal' => 104.90,
                'shipping_cost' => 25.00,
                'total' => 129.90,
                'payment_method' => 'YAPE_PLIN',
                'status' => 'PAID',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hours'))
            ],
            [
                'order_number' => 'JA-2026-8942',
                'customer_name' => 'Daniela Vega',
                'customer_email' => 'daniela.v@hotmail.com',
                'customer_phone' => '+51 993 456 789',
                'shipping_department' => 'Lima',
                'shipping_province' => 'Lima',
                'shipping_district' => 'Miraflores',
                'shipping_address' => 'Calle 2 de Mayo 430, Dpto 502',
                'subtotal' => 65.00,
                'shipping_cost' => 15.00,
                'total' => 80.00,
                'payment_method' => 'TRANSFERENCIA_BCP',
                'status' => 'PENDING',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours'))
            ],
            [
                'order_number' => 'JA-2026-8943',
                'customer_name' => 'Franco Quispe',
                'customer_email' => 'franco.q@gmail.com',
                'customer_phone' => '+51 954 789 123',
                'shipping_department' => 'Arequipa',
                'shipping_province' => 'Arequipa',
                'shipping_district' => 'Yanahuara',
                'shipping_address' => 'Av. Bolognesi 120',
                'subtotal' => 149.80,
                'shipping_cost' => 25.00,
                'total' => 174.80,
                'payment_method' => 'YAPE_PLIN',
                'status' => 'SHIPPED',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 days'))
            ],
            [
                'order_number' => 'JA-2026-8944',
                'customer_name' => 'Renzo Barrientos',
                'customer_email' => 'renzo.diecast@gmail.com',
                'customer_phone' => '+51 987 111 222',
                'shipping_department' => 'Cusco',
                'shipping_province' => 'Cusco',
                'shipping_district' => 'Wanchaq',
                'shipping_address' => 'Av. Garcilaso 320',
                'subtotal' => 45.00,
                'shipping_cost' => 25.00,
                'total' => 70.00,
                'payment_method' => 'YAPE_PLIN',
                'status' => 'PAID',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'order_number' => 'JA-2026-8945',
                'customer_name' => 'Valeria Torres',
                'customer_email' => 'valeria.t@outlook.com',
                'customer_phone' => '+51 944 555 666',
                'shipping_department' => 'La Libertad',
                'shipping_province' => 'Trujillo',
                'shipping_district' => 'Huanchaco',
                'shipping_address' => 'Av. Víctor Larco 780',
                'subtotal' => 84.90,
                'shipping_cost' => 25.00,
                'total' => 109.90,
                'payment_method' => 'TRANSFERENCIA_BCP',
                'status' => 'DELIVERED',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ],
            [
                'order_number' => 'JA-2026-8946',
                'customer_name' => 'Eduardo Salazar',
                'customer_email' => 'eduardo.salazar@gmail.com',
                'customer_phone' => '+51 912 333 444',
                'shipping_department' => 'Lima',
                'shipping_province' => 'Lima',
                'shipping_district' => 'San Isidro',
                'shipping_address' => 'Av. Los Conquistadores 1050',
                'subtotal' => 130.00,
                'shipping_cost' => 15.00,
                'total' => 145.00,
                'payment_method' => 'YAPE_PLIN',
                'status' => 'PROCESSING',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))
            ],
            [
                'order_number' => 'JA-2026-8947',
                'customer_name' => 'Álvaro Huamán',
                'customer_email' => 'alvaro.h@gmail.com',
                'customer_phone' => '+51 982 777 888',
                'shipping_department' => 'Cusco',
                'shipping_province' => 'Urubamba',
                'shipping_district' => 'Urubamba',
                'shipping_address' => 'Calle Ferrocarril 210',
                'subtotal' => 39.90,
                'shipping_cost' => 25.00,
                'total' => 64.90,
                'payment_method' => 'YAPE_PLIN',
                'status' => 'PAID',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ]
        ];

        foreach ($samples as $s) {
            $stmt = $db->prepare("INSERT IGNORE INTO orders (order_number, customer_name, customer_email, customer_phone, shipping_department, shipping_province, shipping_district, shipping_address, subtotal, shipping_cost, total, payment_method, status, created_at) VALUES (:num, :name, :email, :phone, :dept, :prov, :dist, :addr, :sub, :ship, :tot, :pmeth, :status, :created)");
            $stmt->execute([
                'num' => $s['order_number'],
                'name' => $s['customer_name'],
                'email' => $s['customer_email'],
                'phone' => $s['customer_phone'],
                'dept' => $s['shipping_department'],
                'prov' => $s['shipping_province'],
                'dist' => $s['shipping_district'],
                'addr' => $s['shipping_address'],
                'sub' => $s['subtotal'],
                'ship' => $s['shipping_cost'],
                'tot' => $s['total'],
                'pmeth' => $s['payment_method'],
                'status' => $s['status'],
                'created' => $s['created_at']
            ]);

            $orderId = (int)$db->lastInsertId();
            if ($orderId > 0) {
                // Agregar items de ejemplo
                $stmtItem = $db->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_sku, price, quantity, subtotal) VALUES (:oid, 1, 'Nissan Skyline GT-R R34', 'HW-R34-001', 39.90, 1, 39.90)");
                $stmtItem->execute(['oid' => $orderId]);
            }
        }
    }
}