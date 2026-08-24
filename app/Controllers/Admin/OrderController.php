<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Session;
use App\Repositories\OrderRepository;
use App\Services\OrderService;

class OrderController
{
    private OrderService $orderService;
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $this->orderService = new OrderService();
        $this->orderRepo = new OrderRepository();
    }

    public function index(Request $request): void
    {
        $status = (string)$request->query('status', '');
        $orders = $this->orderService->listOrders($status);

        View::render('admin.orders.index', [
            'title' => 'Gestión de Pedidos | J.A ADMIN',
            'orders' => $orders,
            'currentStatus' => $status,
            'success' => Session::get('flash_success')
        ], 'admin');

        Session::remove('flash_success');
    }

    public function show(Request $request, string $id): void
    {
        $order = $this->orderRepo->findById((int)$id);
        if (!$order) {
            Response::redirect('/admin/orders');
        }

        View::render('admin.orders.show', [
            'title' => 'Detalle Pedido #' . $order['order_number'],
            'order' => $order,
            'success' => Session::get('flash_success')
        ], 'admin');

        Session::remove('flash_success');
    }

    public function updateStatus(Request $request, string $id): void
    {
        $newStatus = (string)$request->body('status');
        $this->orderService->updateStatus((int)$id, $newStatus);

        Session::set('flash_success', "Estado del pedido actualizado a: {$newStatus}");
        Response::redirect('/admin/orders/' . $id);
    }
}