<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Session;
use App\Services\InventoryService;

class InventoryController
{
    private InventoryService $inventoryService;

    public function __construct()
    {
        $this->inventoryService = new InventoryService();
    }

    public function index(Request $request): void
    {
        $inventory = $this->inventoryService->listAll();
        $movements = $this->inventoryService->listRecentMovements();

        View::render('admin.inventory.index', [
            'title' => 'Control de Inventario & Kardex | J.A ADMIN',
            'inventory' => $inventory,
            'movements' => $movements,
            'success' => Session::get('flash_success'),
            'error' => Session::get('flash_error')
        ], 'admin');

        Session::remove('flash_success');
        Session::remove('flash_error');
    }

    public function adjust(Request $request): void
    {
        $productId = (int)$request->body('product_id');
        $qtyChange = (int)$request->body('quantity_change');
        $type = (string)$request->body('type', 'ADJUSTMENT');
        $reason = (string)$request->body('reason', 'Ajuste manual desde panel');

        $user = Session::get('user');
        $userId = $user ? (int)$user['id'] : null;

        $result = $this->inventoryService->adjustStock($productId, $qtyChange, $type, $reason, $userId);

        if ($result['success']) {
            Session::set('flash_success', $result['message']);
        } else {
            Session::set('flash_error', $result['message']);
        }

        Response::redirect('/admin/inventory');
    }
}