<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Database;
use PDO;

class DatabaseSeeder
{
    public static function run(): void
    {
        $db = Database::getConnection();

        echo "[SEEDER] Iniciando carga de datos iniciales para J.A COLLECTION...\n";

        // 1. Crear Usuario Administrador
        $adminEmail = 'admin@jacollection.com';
        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $adminEmail]);
        if (!$stmt->fetch()) {
            $hashedPassword = password_hash('AdminJa2026!', PASSWORD_BCRYPT);
            $stmt = $db->prepare("INSERT INTO users (name, lastname, email, phone, password, role, status) VALUES (:name, :lastname, :email, :phone, :password, :role, :status)");
            $stmt->execute([
                'name' => 'Josuee',
                'lastname' => 'Abrahan',
                'email' => $adminEmail,
                'phone' => '+51900000000',
                'password' => $hashedPassword,
                'role' => 'ADMIN',
                'status' => 'ACTIVE'
            ]);
            echo "  -> Usuario Administrador creado: admin@jacollection.com (Clave: AdminJa2026!)\n";
        }

        // 2. Marcas Iniciales
        $brands = [
            ['name' => 'Hot Wheels', 'slug' => 'hot-wheels', 'desc' => 'Línea de coleccionismo Diecast Mattel.'],
            ['name' => 'Mini GT', 'slug' => 'mini-gt', 'desc' => 'TSM Model, alta precisión en escala 1:64.'],
            ['name' => 'Matchbox', 'slug' => 'matchbox', 'desc' => 'Coleccionables clásicos y modernos.'],
            ['name' => 'Tomica', 'slug' => 'tomica', 'desc' => 'Modelos y ediciones exclusivas Takara Tomy.'],
            ['name' => 'GreenLight', 'slug' => 'greenlight', 'desc' => 'Modelos diecast de cine y automovilismo estadounidense.'],
            ['name' => 'Maisto', 'slug' => 'maisto', 'desc' => 'Réplicas a escala de alta fidelidad.']
        ];

        foreach ($brands as $b) {
            $stmt = $db->prepare("INSERT IGNORE INTO brands (name, slug, description) VALUES (:name, :slug, :description)");
            $stmt->execute(['name' => $b['name'], 'slug' => $b['slug'], 'description' => $b['desc']]);
        }
        echo "  -> Marcas iniciales registradas.\n";

        // 3. Categorías Iniciales
        $categories = [
            ['name' => 'Mainline', 'slug' => 'mainline', 'desc' => 'Línea básica y variantes coleccionables.'],
            ['name' => 'Premium', 'slug' => 'premium', 'desc' => 'Carrocería y base de metal con neumáticos de goma Real Riders.'],
            ['name' => 'Treasure Hunt', 'slug' => 'treasure-hunt', 'desc' => 'Ediciones especiales y Super Treasure Hunts (STH).'],
            ['name' => 'JDM Specials', 'slug' => 'jdm-specials', 'desc' => 'Vehículos icónicos del mercado japonés (Skyline, Supra, RX-7, NSX).'],
            ['name' => 'Supercars & Hypercars', 'slug' => 'supercars', 'desc' => 'Modelos de alto rendimiento y exóticos.'],
            ['name' => 'American Muscle', 'slug' => 'muscle', 'desc' => 'Clásicos y potentes V8 americanos.']
        ];

        foreach ($categories as $c) {
            $stmt = $db->prepare("INSERT IGNORE INTO categories (name, slug, description) VALUES (:name, :slug, :description)");
            $stmt->execute(['name' => $c['name'], 'slug' => $c['slug'], 'description' => $c['desc']]);
        }
        echo "  -> Categorías iniciales registradas.\n";

        // 4. Productos de Muestra
        $products = [
            [
                'sku' => 'HW-R34-001',
                'name' => 'Nissan Skyline GT-R (BNR34)',
                'slug' => 'nissan-skyline-gt-r-bnr34',
                'brand' => 'hot-wheels',
                'cat' => 'premium',
                'scale' => '1:64',
                'model' => 'Skyline R34',
                'price' => 39.90,
                'stock' => 8,
                'featured' => 1,
                'new' => 1
            ],
            [
                'sku' => 'MGT-P911-002',
                'name' => 'Porsche 911 GT3 RS (992)',
                'slug' => 'porsche-911-gt3-rs-992',
                'brand' => 'mini-gt',
                'cat' => 'supercars',
                'scale' => '1:64',
                'model' => '911 GT3 RS',
                'price' => 65.00,
                'stock' => 5,
                'featured' => 1,
                'new' => 1
            ],
            [
                'sku' => 'MBX-FJ40-003',
                'name' => 'Toyota Land Cruiser FJ40',
                'slug' => 'toyota-land-cruiser-fj40',
                'brand' => 'matchbox',
                'cat' => 'mainline',
                'scale' => '1:64',
                'model' => 'Land Cruiser FJ40',
                'price' => 45.00,
                'stock' => 12,
                'featured' => 1,
                'new' => 0
            ]
        ];

        foreach ($products as $p) {
            $stmtB = $db->prepare("SELECT id FROM brands WHERE slug = :slug_brand");
            $stmtB->execute(['slug_brand' => $p['brand']]);
            $brandId = $stmtB->fetchColumn();

            $stmtC = $db->prepare("SELECT id FROM categories WHERE slug = :slug_cat");
            $stmtC->execute(['slug_cat' => $p['cat']]);
            $catId = $stmtC->fetchColumn();

            if ($brandId && $catId) {
                $stmtP = $db->prepare("INSERT IGNORE INTO products (sku, name, slug, brand_id, category_id, scale, model, price, is_featured, is_new, status) VALUES (:sku, :name, :slug, :brand_id, :category_id, :scale, :model, :price, :is_featured, :is_new, 'ACTIVE')");
                $stmtP->execute([
                    'sku' => $p['sku'],
                    'name' => $p['name'],
                    'slug' => $p['slug'],
                    'brand_id' => (int)$brandId,
                    'category_id' => (int)$catId,
                    'scale' => $p['scale'],
                    'model' => $p['model'],
                    'price' => $p['price'],
                    'is_featured' => $p['featured'],
                    'is_new' => $p['new']
                ]);

                $stmtGetId = $db->prepare("SELECT id FROM products WHERE sku = :sku_search");
                $stmtGetId->execute(['sku_search' => $p['sku']]);
                $productId = (int)$stmtGetId->fetchColumn();

                if ($productId > 0) {
                    $stmtInv = $db->prepare("INSERT INTO inventory (product_id, stock_current, stock_reserved, minimum_stock, status) VALUES (:pid, :stock_val, 0, 2, 'DISPONIBLE') ON DUPLICATE KEY UPDATE stock_current = :stock_update");
                    $stmtInv->execute([
                        'pid' => $productId,
                        'stock_val' => $p['stock'],
                        'stock_update' => $p['stock']
                    ]);

                    $stmtMov = $db->prepare("INSERT INTO inventory_movements (product_id, type, quantity, stock_before, stock_after, reason) VALUES (:prod_id, 'IMPORT', :qty_movement, 0, :qty_after, 'Stock inicial seeder')");
                    $stmtMov->execute([
                        'prod_id' => $productId,
                        'qty_movement' => $p['stock'],
                        'qty_after' => $p['stock']
                    ]);
                }
            }
        }
        echo "  -> Productos iniciales e inventario configurados.\n";
        echo "[SEEDER COMPLETADO CON ÉXITO]\n";
    }
}