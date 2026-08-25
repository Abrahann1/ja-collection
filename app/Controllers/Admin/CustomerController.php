<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Session;
use App\Core\Database;
use PDO;

class CustomerController
{
    public function index(Request $request): void
    {
        $db = Database::getConnection();

        // Consulta de clientes con métricas de compras agregadas
        $sql = "SELECT u.id, u.name, u.lastname, u.email, u.phone, u.status, u.created_at,
                       COUNT(o.id) AS total_orders,
                       COALESCE(SUM(o.total), 0) AS total_spent
                FROM users u
                LEFT JOIN orders o ON (u.id = o.user_id OR u.email = o.customer_email) AND o.status != 'CANCELLED'
                WHERE u.role = 'CUSTOMER'
                GROUP BY u.id
                ORDER BY total_spent DESC, u.id DESC";

        $customers = $db->query($sql)->fetchAll();

        View::render('admin.customers.index', [
            'title' => 'Gestión de Clientes & Coleccionistas | J.A ADMIN',
            'customers' => $customers,
            'success' => Session::get('flash_success')
        ], 'admin');

        Session::remove('flash_success');
    }

    public function toggleStatus(Request $request, string $id): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT status FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => (int)$id]);
        $currentStatus = (string)$stmt->fetchColumn();

        $newStatus = $currentStatus === 'ACTIVE' ? 'BANNED' : 'ACTIVE';
        $stmtUp = $db->prepare("UPDATE users SET status = :status WHERE id = :id");
        $stmtUp->execute(['status' => $newStatus, 'id' => (int)$id]);

        Session::set('flash_success', "Estado del cliente actualizado a: {$newStatus}");
        Response::redirect('/admin/customers');
    }
}