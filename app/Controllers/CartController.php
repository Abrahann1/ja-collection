<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;

class CartController
{
    public function index(Request $request): void
    {
        View::render('pages.cart', [
            'title' => 'Bolsa de Compras | J.A COLLECTION',
            'extraCss' => 'cart'
        ]);
    }
}