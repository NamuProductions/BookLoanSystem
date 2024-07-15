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
            $row['email'],
            $row['password'],
            $row['role'],
            isset($row['age']) ? (int)$row['age'] : null
        );
    }

    public function save(User $user): void
    {
        $stmt = $this->pdo->prepare('REPLACE INTO users (user_id, user_name, email, password, role, age) VALUES (:userId, :userName, :email, :password, :role, :age)');
        $stmt->execute([
            'userId' => $user->userId(),
            'userName' => $user->userName(),
            'email' => $user->email(),
            'password' => $user->password(),
            'role' => $user->role(),
            'age' => $user->age(),
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
            $row['user_id'],
            $row['user_name'],
            $row['email'],
            $row['password'],
            $row['role'],
            isset($row['age']) ? (int)$row['age'] : null
        );
    }
}
