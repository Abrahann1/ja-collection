<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Session;
use App\Core\Database;
use PDO;

class AccountController
{
    public function index(Request $request): void
    {
        $user = Session::get('user');
        if (!$user) {
            Response::redirect('/login');
        }

        $db = Database::getConnection();

        // 1. Obtener pedidos del cliente
        $stmtOrders = $db->prepare("SELECT * FROM orders WHERE user_id = :uid OR customer_email = :email ORDER BY id DESC");
        $stmtOrders->execute([
            'uid' => (int)$user['id'],
            'email' => $user['email']
        ]);
        $myOrders = $stmtOrders->fetchAll();

        // 2. Obtener datos actualizados del usuario
        $stmtUser = $db->prepare("SELECT id, name, lastname, email, phone, role, created_at FROM users WHERE id = :id LIMIT 1");
        $stmtUser->execute(['id' => (int)$user['id']]);
        $freshUser = $stmtUser->fetch() ?: $user;

        View::render('pages.account', [
            'title' => 'Mi Cuenta de Coleccionista | J.A COLLECTION',
            'user' => $freshUser,
            'orders' => $myOrders,
            'success' => Session::get('flash_success'),
            'error' => Session::get('flash_error')
        ]);

        Session::remove('flash_success');
        Session::remove('flash_error');
    }

    public function updateProfile(Request $request): void
    {
        $user = Session::get('user');
        if (!$user) Response::redirect('/login');

        $name = trim((string)$request->body('name'));
        $lastname = trim((string)$request->body('lastname'));
        $phone = trim((string)$request->body('phone'));

        if (empty($name) || empty($lastname)) {
            Session::set('flash_error', 'Nombre y apellido son obligatorios.');
            Response::redirect('/account');
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET name = :name, lastname = :lastname, phone = :phone WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'lastname' => $lastname,
            'phone' => $phone,
            'id' => (int)$user['id']
        ]);

        // Actualizar sesión
        $user['name'] = $name;
        $user['lastname'] = $lastname;
        $user['phone'] = $phone;
        Session::set('user', $user);

        Session::set('flash_success', 'Datos personales actualizados correctamente.');
        Response::redirect('/account');
    }

    public function updatePassword(Request $request): void
    {
        $user = Session::get('user');
        if (!$user) Response::redirect('/login');

        $currentPass = (string)$request->body('current_password');
        $newPass = (string)$request->body('new_password');
        $confirmPass = (string)$request->body('confirm_password');

        if (strlen($newPass) < 8) {
            Session::set('flash_error', 'La nueva contraseña debe tener al menos 8 caracteres.');
            Response::redirect('/account');
        }

        if ($newPass !== $confirmPass) {
            Session::set('flash_error', 'Las nuevas contraseñas no coinciden.');
            Response::redirect('/account');
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT password FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => (int)$user['id']]);
        $hash = (string)$stmt->fetchColumn();

        if (!password_verify($currentPass, $hash)) {
            Session::set('flash_error', 'La contraseña actual no es correcta.');
            Response::redirect('/account');
        }

        $newHash = password_hash($newPass, PASSWORD_BCRYPT);
        $stmtUp = $db->prepare("UPDATE users SET password = :p WHERE id = :id");
        $stmtUp->execute(['p' => $newHash, 'id' => (int)$user['id']]);

        Session::set('flash_success', 'Tu contraseña ha sido actualizada con éxito.');
        Response::redirect('/account');
    }
}