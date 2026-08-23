<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Session;
use App\Repositories\UserRepository;

class AuthService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function register(array $data): array
    {
        if (empty($data['name']) || empty($data['lastname']) || empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados.'];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'El correo electrónico no tiene un formato válido.'];
        }

        if (strlen((string)$data['password']) < 8) {
            return ['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres.'];
        }

        if ($this->userRepo->findByEmail($data['email'])) {
            return ['success' => false, 'message' => 'El correo electrónico ya se encuentra registrado.'];
        }

        $hashedPassword = password_hash((string)$data['password'], PASSWORD_BCRYPT);
        $data['password'] = $hashedPassword;
        $data['role'] = 'CUSTOMER';

        $userId = $this->userRepo->create($data);
        $user = $this->userRepo->findById($userId);

        Session::regenerate();
        Session::set('user', $user);

        return ['success' => true, 'message' => 'Registro completado con éxito.', 'user' => $user];
    }

    public function login(string $email, string $password, mixed $requiredRole = null): array
    {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Ingrese correo y contraseña.'];
        }

        $user = $this->userRepo->findByEmail($email);

        if (!$user || !password_verify($password, (string)$user['password'])) {
            return ['success' => false, 'message' => 'Credenciales de acceso incorrectas.'];
        }

        if ($user['status'] !== 'ACTIVE') {
            return ['success' => false, 'message' => 'Su cuenta se encuentra inactiva o suspendida.'];
        }

        if ($requiredRole === 'ADMIN' && !in_array($user['role'], ['ADMIN', 'STAFF'], true)) {
            return ['success' => false, 'message' => 'Acceso denegado. No posee permisos de administrador.'];
        }

        unset($user['password']);

        Session::regenerate();
        Session::set('user', $user);

        return ['success' => true, 'message' => 'Inicio de sesión exitoso.', 'user' => $user];
    }

    public function logout(): void
    {
        Session::destroy();
    }

    public function getCurrentUser(): mixed
    {
        return Session::get('user');
    }
}