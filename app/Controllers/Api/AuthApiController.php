<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

class AuthApiController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(Request $request): void
    {
        $email = (string)$request->body('email');
        $password = (string)$request->body('password');
        $isAdmin = (bool)$request->body('is_admin', false);

        $result = $this->authService->login($email, $password, $isAdmin ? 'ADMIN' : null);

        Response::json($result, $result['success'] ? 200 : 401);
    }

    public function register(Request $request): void
    {
        $data = [
            'name' => (string)$request->body('name'),
            'lastname' => (string)$request->body('lastname'),
            'email' => (string)$request->body('email'),
            'phone' => (string)$request->body('phone'),
            'password' => (string)$request->body('password')
        ];

        $result = $this->authService->register($data);

        Response::json($result, $result['success'] ? 201 : 422);
    }

    public function me(Request $request): void
    {
        $user = $this->authService->getCurrentUser();

        if (!$user) {
            Response::json(['success' => false, 'message' => 'No autenticado'], 401);
            return;
        }

        Response::json(['success' => true, 'user' => $user]);
    }

    public function logout(Request $request): void
    {
        $this->authService->logout();
        Response::json(['success' => true, 'message' => 'Sesión finalizada']);
    }
}