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
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE userId = :userId');
        $stmt->execute(['userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return new User($row['userName'], $row['email'], $row['password'], $row['role'], $row['userId']);
    }

    public function save(User $user): void
    {
        $stmt = $this->pdo->prepare('REPLACE INTO users (userId, userName, email, password, role) VALUES (:userId, :userName, :email, :password, :role)');
        $stmt->execute([
            'userId' => $user->getUserId(),
            'userName' => $user->getUserName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
        ]);
    }

    public function findByUserName(string $username): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE userName = :userName');
        $stmt->execute(['userName' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return new User($row['userName'], $row['email'], $row['password'], $row['role'], $row['userId']);
    }
}
