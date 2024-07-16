<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use PDO;

class PdoUserRepository implements UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(string $userId): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return new User(
            $row['user_id'],
            $row['user_name'],
            $row['password'],
            $row['email'],
            isset($row['age']) ? (int)$row['age'] : null,
            $row['role'],
        );
    }

    public function save(User $user): void
    {
        $stmt = $this->pdo->prepare('REPLACE INTO users (user_id, user_name, password, email, full_name, age, role) VALUES (:userId, :userName, :password, :email, full_name, age, :role)');
        $stmt->execute([
            'userId' => $user->userId(),
            'userName' => $user->userName(),
            'password' => $user->password(),
            'email' => $user->email(),
            'fullName' => $user->fullName(),
            'age' => $user->age(),
            'role' => $user->role(),
        ]);
    }

    public function findByUserName(string $username): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE user_name = :user_name');
        $stmt->execute(['user_name' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return new User(
            $row['user_name'],
            $row['password'],
            $row['email'],
            $row['full_name'],
            isset($row['age']) ? (int)$row['age'] : null,
            $row['role'],
            $row['user_id'],
        );
    }
}
