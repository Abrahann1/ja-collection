<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AdminMiddleware
{
    public function handle(Request $request): bool
    {
        Session::start();
        $user = Session::get('user');

        if (!$user) {
            if (str_starts_with($request->getUri(), '/api')) {
                Response::json(['success' => false, 'message' => 'Acceso no autorizado.'], 403);
            } else {
                Response::redirect('/admin/login');
            }
            return false;
        }

        $role = (string)($user['role'] ?? '');
        if ($role !== 'ADMIN' and $role !== 'STAFF') {
            if (str_starts_with($request->getUri(), '/api')) {
                Response::json(['success' => false, 'message' => 'Permisos insuficientes.'], 403);
            } else {
                Response::redirect('/admin/login');
            }
            return false;
        }

        return true;
    }
}