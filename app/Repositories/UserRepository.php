<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt =$this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => strtolower(trim($email))]);
        $user =$stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt =$this->db->prepare("SELECT id, name, lastname, email, phone, role, status, created_at FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' =>$id]);
        $user =$stmt->fetch();
        return $user ?: null;
    }

    public function create(array $data): int
    {
        $stmt =$this->db->prepare("INSERT INTO users (name, lastname, email, phone, password, role, status) VALUES (:name, :lastname, :email, :phone, :password, :role, 'ACTIVE')");
        $stmt->execute([
            'name' => trim($data['name']),
            'lastname' => trim($data['lastname']),
            'email' => strtolower(trim($data['email'])),
            'phone' => trim($data['phone'] ?? ''),
            'password' => $data['password'],
            'role' => $data['role'] ?? 'CUSTOMER'
        ]);

        return (int)$this->db->lastInsertId();
    }
}