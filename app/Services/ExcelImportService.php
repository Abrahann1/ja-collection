<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Repositories\ProductRepository;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\InventoryRepository;
use Exception;
use PDO;

class ExcelImportService
{
    private ProductRepository $productRepo;
    private BrandRepository $brandRepo;
    private CategoryRepository $categoryRepo;
    private InventoryRepository $inventoryRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->brandRepo = new BrandRepository();
        $this->categoryRepo = new CategoryRepository();
        $this->inventoryRepo = new InventoryRepository();
    }

    public function parseUploadedFile(string $filePath): array
    {
        $rows = [];
        
        // Si PhpSpreadsheet está disponible
        if (class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rawRows = $sheet->toArray();
            
            if (count($rawRows) > 1) {
                $headers = array_map(function($h) { return strtolower(trim((string)$h)); }, $rawRows[0]);
                for ($i = 1; $i < count($rawRows); $i++) {
                    $row = $rawRows[$i];
                    if (empty(array_filter($row))) continue;
                    $rowData = [];
                    foreach ($headers as $index => $key) {
                        $rowData[$key] = $row[$index] ?? '';
                    }
                    $rows[] = $rowData;
                }
            }
        } else {
            // Lector CSV / Fallback
            $handle = fopen($filePath, "r");
            if ($handle !== false) {
                $headers = fgetcsv($handle, 2000, ",");
                if ($headers) {
                    $headers = array_map(function($h) { return strtolower(trim((string)$h)); }, $headers);
                    while (($data = fgetcsv($handle, 2000, ",")) !== false) {
                        if (empty(array_filter($data))) continue;
                        $rowData = [];
                        foreach ($headers as $index => $key) {
                            $rowData[$key] = $data[$index] ?? '';
                        }
                        $rows[] = $rowData;
                    }
                }
                fclose($handle);
            }
        }

        return $this->validateRows($rows);
    }

    private function validateRows(array $rows): array
    {
        $valid = [];
        $invalid = [];
        $seenSkus = [];

        foreach ($rows as $index => $row) {
            $lineNum = $index + 2;
            $sku = strtoupper(trim((string)($row['sku'] ?? '')));
            $name = trim((string)($row['name'] ?? ''));
            $brand = trim((string)($row['brand'] ?? 'Hot Wheels'));
            $category = trim((string)($row['category'] ?? 'Mainline'));
            $scale = trim((string)($row['scale'] ?? '1:64'));
            $price = (float)($row['price'] ?? 0);
            $stock = (int)($row['stock'] ?? 0);

            $errors = [];

            if (empty($sku)) {
                $errors[] = "Fila {$lineNum}: El SKU es obligatorio.";
            } elseif (isset($seenSkus[$sku])) {
                $errors[] = "Fila {$lineNum}: El SKU '{$sku}' está duplicado en el mismo archivo.";
            } elseif (!$this->productRepo->isSkuUnique($sku)) {
                $errors[] = "Fila {$lineNum}: El SKU '{$sku}' ya existe en la base de datos.";
            } else {
                $seenSkus[$sku] = true;
            }

            if (empty($name)) {
                $errors[] = "Fila {$lineNum}: El nombre del modelo es obligatorio.";
            }

            if ($price <= 0) {
                $errors[] = "Fila {$lineNum}: El precio debe ser mayor a 0.";
            }

            if (!empty($errors)) {
                $invalid[] = ['row' => $lineNum, 'sku' => $sku, 'name' => $name, 'errors' => $errors];
            } else {
                $valid[] = [
                    'sku' => $sku,
                    'name' => $name,
                    'brand' => $brand,
                    'category' => $category,
                    'scale' => $scale,
                    'model' => trim((string)($row['model'] ?? '')),
                    'description' => trim((string)($row['description'] ?? '')),
                    'price' => $price,
                    'old_price' => !empty($row['old_price']) ? (float)$row['old_price'] : null,
                    'stock' => max(0, $stock),
                    'minimum_stock' => max(1, (int)($row['minimum_stock'] ?? 2)),
                    'is_featured' => !empty($row['featured']) ? 1 : 0,
                    'is_new' => !empty($row['new']) ? 1 : 1
                ];
            }
        }

        return [
            'valid' => $valid,
            'invalid' => $invalid,
            'total_processed' => count($rows)
        ];
    }

    public function executeImport(array $validRows, ?int $userId = null): array
    {
        $db = Database::getConnection();
        $importedCount = 0;

        try {
            $db->beginTransaction();

            $brandCache = [];
            $catCache = [];

            foreach ($validRows as $p) {
                $brandName = $p['brand'];
                $catName = $p['category'];

                // 1. Obtener o crear Marca
                if (!isset($brandCache[$brandName])) {
                    $slugB = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $brandName), '-'));
                    $stmtB = $db->prepare("SELECT id FROM brands WHERE slug = :slug LIMIT 1");
                    $stmtB->execute(['slug' => $slugB]);
                    $bId = $stmtB->fetchColumn();

                    if (!$bId) {
                        $stmtInsB = $db->prepare("INSERT INTO brands (name, slug, status) VALUES (:name, :slug, 'ACTIVE')");
                        $stmtInsB->execute(['name' => $brandName, 'slug' => $slugB]);
                        $bId = (int)$db->lastInsertId();
                    }
                    $brandCache[$brandName] = (int)$bId;
                }
                $brandId = $brandCache[$brandName];

                // 2. Obtener o crear Categoría
                if (!isset($catCache[$catName])) {
                    $slugC = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $catName), '-'));
                    $stmtC = $db->prepare("SELECT id FROM categories WHERE slug = :slug LIMIT 1");
                    $stmtC->execute(['slug' => $slugC]);
                    $cId = $stmtC->fetchColumn();

                    if (!$cId) {
                        $stmtInsC = $db->prepare("INSERT INTO categories (name, slug, status) VALUES (:name, :slug, 'ACTIVE')");
                        $stmtInsC->execute(['name' => $catName, 'slug' => $slugC]);
                        $cId = (int)$db->lastInsertId();
                    }
                    $catCache[$catName] = (int)$cId;
                }
                $catId = $catCache[$catName];

                // 3. Insertar Producto
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $p['name']), '-')) . '-' . strtolower($p['sku']);
                $stmtP = $db->prepare("INSERT INTO products (sku, name, slug, brand_id, category_id, scale, model, description, price, old_price, is_featured, is_new, status) VALUES (:sku, :name, :slug, :bid, :cid, :scale, :model, :desc, :price, :old_price, :feat, :new, 'ACTIVE')");
                $stmtP->execute([
                    'sku' => $p['sku'],
                    'name' => $p['name'],
                    'slug' => $slug,
                    'bid' => $brandId,
                    'cid' => $catId,
                    'scale' => $p['scale'],
                    'model' => $p['model'],
                    'desc' => $p['description'],
                    'price' => $p['price'],
                    'old_price' => $p['old_price'],
                    'feat' => $p['is_featured'],
                    'new' => $p['is_new']
                ]);

                $productId = (int)$db->lastInsertId();

                // 4. Crear Inventario Inicial
                $stock = (int)$p['stock'];
                $minStock = (int)$p['minimum_stock'];
                $status = $stock > $minStock ? 'DISPONIBLE' : ($stock > 0 ? 'STOCK_BAJO' : 'AGOTADO');

                $stmtInv = $db->prepare("INSERT INTO inventory (product_id, stock_current, stock_reserved, minimum_stock, status) VALUES (:pid, :curr, 0, :min, :status)");
                $stmtInv->execute([
                    'pid' => $productId,
                    'curr' => $stock,
                    'min' => $minStock,
                    'status' => $status
                ]);

                // 5. Registrar Kardex
                if ($stock > 0) {
                    $this->inventoryRepo->logMovement($productId, 'IMPORT', $stock, 0, $stock, 'Importación masiva Excel', $userId);
                }

                $importedCount++;
            }

            $db->commit();
            return ['success' => true, 'count' => $importedCount];

        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Error durante la transacción de importación: ' . $e->getMessage()];
        }
    }
}