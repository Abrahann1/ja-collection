<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Repositories\ProductRepository;
use App\Repositories\InventoryRepository;
use App\Validators\ProductValidator;
use Exception;

class ProductService
{
    private ProductRepository $productRepo;
    private InventoryRepository $inventoryRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->inventoryRepo = new InventoryRepository();
    }

    public function listCatalog(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $data = $this->productRepo->getPaginated($filters, $page, $perPage, true);

        foreach ($data['items'] as &$item) {
            $item['price_formatted'] = 'S/ ' . number_format((float)$item['price'], 2);
            $item['old_price_formatted'] = $item['old_price'] ? 'S/ ' . number_format((float)$item['old_price'], 2) : null;
            $item['is_in_stock'] = (int)$item['stock_available'] > 0;
        }

        return $data;
    }

    public function listAdminProducts(array $filters = [], int $page = 1, int $perPage = 15): array
    {
        $data = $this->productRepo->getPaginated($filters, $page, $perPage, false);

        foreach ($data['items'] as &$item) {
            $item['price_formatted'] = 'S/ ' . number_format((float)$item['price'], 2);
        }

        return $data;
    }

    public function getProductDetail(mixed $identifier): mixed
    {
        $product = $this->productRepo->findByIdOrSlug($identifier);
        if (!$product) {
            return null;
        }

        $product['price_formatted'] = 'S/ ' . number_format((float)$product['price'], 2);
        $product['old_price_formatted'] = $product['old_price'] ? 'S/ ' . number_format((float)$product['old_price'], 2) : null;
        $product['is_low_stock'] = (int)$product['stock_available'] > 0 && (int)$product['stock_available'] <= (int)$product['minimum_stock'];
        $product['is_out_of_stock'] = (int)$product['stock_available'] <= 0;

        return $product;
    }

    public function createProduct(array $data, ?int $userId = null): array
    {
        $errors = ProductValidator::validate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if (!$this->productRepo->isSkuUnique($data['sku'])) {
            return ['success' => false, 'errors' => ['El código SKU ya existe en la base de datos.']];
        }

        $data['slug'] = $this->generateSlug($data['name'], $data['sku']);
        $initialStock = max(0, (int)($data['stock'] ?? 0));
        $minStock = max(1, (int)($data['minimum_stock'] ?? 2));

        $db = Database::getConnection();
        try {
            $db->beginTransaction();

            $productId = $this->productRepo->create($data);

            $status = $initialStock > $minStock ? 'DISPONIBLE' : ($initialStock > 0 ? 'STOCK_BAJO' : 'AGOTADO');
            $stmtInv = $db->prepare("INSERT INTO inventory (product_id, stock_current, stock_reserved, minimum_stock, status) VALUES (:pid, :current, 0, :min, :status)");
            $stmtInv->execute([
                'pid' => $productId,
                'current' => $initialStock,
                'min' => $minStock,
                'status' => $status
            ]);

            if ($initialStock > 0) {
                $this->inventoryRepo->logMovement($productId, 'PURCHASE', $initialStock, 0, $initialStock, 'Stock inicial al crear producto', $userId);
            }

            $db->commit();
            return ['success' => true, 'product_id' => $productId, 'message' => 'Producto creado con éxito.'];

        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'errors' => ['Error al guardar en base de datos: ' . $e->getMessage()]];
        }
    }

    public function updateProduct(int $id, array $data): array
    {
        $errors = ProductValidator::validate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if (!$this->productRepo->isSkuUnique($data['sku'], $id)) {
            return ['success' => false, 'errors' => ['El código SKU ya pertenece a otro modelo.']];
        }

        $data['slug'] = $this->generateSlug($data['name'], $data['sku']);
        $success = $this->productRepo->update($id, $data);

        return $success 
            ? ['success' => true, 'message' => 'Producto actualizado correctamente.']
            : ['success' => false, 'errors' => ['No se pudo actualizar el producto.']];
    }

    public function duplicateProduct(int $id, ?int $userId = null): array
    {
        $original = $this->productRepo->findByIdOrSlug($id);
        if (!$original) {
            return ['success' => false, 'errors' => ['Producto original no encontrado.']];
        }

        $copyData = $original;
        $copyData['name'] = $original['name'] . ' (Copia)';
        $copyData['sku'] = $original['sku'] . '-COPY-' . strtoupper(substr(uniqid(), -4));
        $copyData['stock'] = 0;

        return $this->createProduct($copyData, $userId);
    }

    public function deleteProduct(int $id): array
    {
        $success = $this->productRepo->delete($id);
        return $success 
            ? ['success' => true, 'message' => 'Producto archivado con éxito.']
            : ['success' => false, 'errors' => ['No se pudo archivar el producto.']];
    }

    private function generateSlug(string $name, string $sku): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $skuPart = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $sku), '-'));
        return $slug . '-' . $skuPart;
    }
}