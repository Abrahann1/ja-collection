<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(Request $request): bool
    {
        Session::start();
        $user = Session::get('user');

        if (!$user) {
            if (str_starts_with($request->getUri(), '/api')) {
                Response::json(['success' => false, 'message' => 'No autenticado. Inicie sesión.'], 401);
            } else {
                Response::redirect('/login');
            }
            return false;
        }

        return true;
    }
}