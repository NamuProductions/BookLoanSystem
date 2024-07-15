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
        $stmt = $this->pdo->prepare('SELECT * FROM library.users WHERE user_id = :userId');
        $stmt->execute(['userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return new User($row['userName'], $row['email'], $row['password'], $row['role'], $row['userId']);
    }

    public function save(User $user): void
    {
        $stmt = $this->pdo->prepare('REPLACE INTO users (user_id, user_name, email, password, role) VALUES (:userId, :userName, :email, :password, :role)');
        $stmt->execute([
            'userId' => $user->userId(),
            'userName' => $user->userName(),
            'email' => $user->email(),
            'password' => $user->password(),
            'role' => $user->role(),
        ]);
    }

    public function findByUserName(string $username): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE user_name = :userName');
        $stmt->execute(['userName' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }

        return new User($row['userName'], $row['email'], $row['password'], $row['role'], $row['userId']);
    }
}
