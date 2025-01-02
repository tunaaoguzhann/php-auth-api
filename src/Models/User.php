<?php

namespace Tunaaoguzhann\PhpAuthApi\Models;

use PDO;
use Tunaaoguzhann\PhpAuthApi\Config\Database;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
        $stmt->execute([
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        
        $user = $stmt->fetch();
        return $user ?: null;
    }
} 