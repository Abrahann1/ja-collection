<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Session;
use App\Helpers\SettingsHelper;
use App\Services\OrderService;

class CheckoutController
{
    private OrderService $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function index(Request $request): void
    {
        $user = Session::get('user');
        $settings = SettingsHelper::all();

        View::render('pages.checkout', [
            'title' => 'Finalizar Pedido | J.A COLLECTION',
            'user' => $user,
            'settings' => $settings
        ]);
    }

    public function process(Request $request): void
    {
        $settings = SettingsHelper::all();
        $dept = (string)$request->body('shipping_department', 'Cusco');
        
        // Flete dinámico según configuración de la BD
        $isLima = in_array(strtolower(trim($dept)), ['lima', 'callao'], true);
        $shippingCost = $isLima ? (float)$settings['shipping_lima'] : (float)$settings['shipping_provincia'];

        $customerData = [
            'customer_name' => (string)$request->body('customer_name'),
            'customer_email' => (string)$request->body('customer_email'),
            'customer_phone' => (string)$request->body('customer_phone'),
            'shipping_department' => $dept,
            'shipping_province' => (string)$request->body('shipping_province'),
            'shipping_district' => (string)$request->body('shipping_district'),
            'shipping_address' => (string)$request->body('shipping_address'),
            'shipping_cost' => $shippingCost,
            'payment_method' => (string)$request->body('payment_method', 'YAPE_PLIN'),
            'notes' => (string)$request->body('notes', '')
        ];

        $cartJson = (string)$request->body('cart_data', '[]');
        $cartItems = json_decode($cartJson, true) ?: [];

        $user = Session::get('user');
        $userId = $user ? (int)$user['id'] : null;

        $result = $this->orderService->processCheckout($customerData, $cartItems, $userId);

        if (!$result['success']) {
            View::render('pages.checkout', [
                'title' => 'Finalizar Pedido | J.A COLLECTION',
                'error' => $result['message'],
                'data' => $customerData,
                'user' => $user,
                'settings' => $settings
            ]);
            return;
        }

        Response::redirect('/orders/success?order=' . urlencode($result['order_number']));
    }

    public function success(Request $request): void
    {
        $orderNum = (string)$request->query('order', '');
        $order = $this->orderService->getOrderDetails($orderNum);

        if (!$order) {
            Response::redirect('/');
        }

        $settings = SettingsHelper::all();

        View::render('pages.order_success', [
            'title' => '¡Pedido Confirmado! #' . $orderNum . ' | J.A COLLECTION',
            'order' => $order,
            'settings' => $settings
        ]);
    }
}