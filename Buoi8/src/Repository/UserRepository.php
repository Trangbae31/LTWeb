<?php

namespace App\Repository;

use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Tìm tài khoản theo email
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT user_id, full_name, email, password_hash, role
                FROM users
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    // Tạo tài khoản mới
    public function create(
        string $fullName,
        string $email,
        string $passwordHash
    ): bool {
        $sql = "INSERT INTO users (full_name, email, password_hash, role)
                VALUES (:full_name, :email, :password_hash, 'user')";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':full_name' => $fullName,
            ':email' => $email,
            ':password_hash' => $passwordHash
        ]);
    }
}