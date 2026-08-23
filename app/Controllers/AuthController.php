<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function showLogin(Request $request): void
    {
        if ($this->authService->getCurrentUser()) {
            Response::redirect('/');
        }
        View::render('pages.login', ['title' => 'Iniciar Sesión | J.A COLLECTION']);
    }

    public function showRegister(Request $request): void
    {
        if ($this->authService->getCurrentUser()) {
            Response::redirect('/');
        }
        View::render('pages.register', ['title' => 'Crear Cuenta | J.A COLLECTION']);
    }

    public function showAdminLogin(Request $request): void
    {
        $user = $this->authService->getCurrentUser();
        if ($user && in_array($user['role'], ['ADMIN', 'STAFF'], true)) {
            Response::redirect('/admin/dashboard');
        }
        // Renderizar login standalone sin el sidebar
        View::render('admin.login', ['title' => 'Acceso Administrativo | J.A COLLECTION'], '');
    }

    public function handleLogin(Request $request): void
    {
        $email = (string)$request->body('email');
        $password = (string)$request->body('password');
        $isAdmin = (bool)$request->body('is_admin', false);

        $result = $this->authService->login($email, $password, $isAdmin ? 'ADMIN' : null);

        if (!$result['success']) {
            if ($isAdmin) {
                View::render('admin.login', ['error' => $result['message'], 'email' => $email], '');
            } else {
                View::render('pages.login', ['error' => $result['message'], 'email' => $email]);
            }
            return;
        }

        Response::redirect($isAdmin ? '/admin/dashboard' : '/');
    }

    public function handleRegister(Request $request): void
    {
        $data = [
            'name' => (string)$request->body('name'),
            'lastname' => (string)$request->body('lastname'),
            'email' => (string)$request->body('email'),
            'phone' => (string)$request->body('phone'),
            'password' => (string)$request->body('password')
        ];

        $result = $this->authService->register($data);

        if (!$result['success']) {
            View::render('pages.register', ['error' => $result['message'], 'data' => $data]);
            return;
        }

        Response::redirect('/');
    }

    public function logout(Request $request): void
    {
        $this->authService->logout();
        Response::redirect('/');
    }

    public function adminLogout(Request $request): void
    {
        $this->authService->logout();
        Response::redirect('/admin/login');
    }
}