<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\UserName;
use DateTime;
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

        return $this->mapRowToUser($row);
    }

    public function save(User $user): void
    {
            $stmt = $this->pdo->prepare('REPLACE INTO users (user_id, user_name, password, email, full_name, age, role) 
                                         VALUES (:userId, :userName, :password, :email, :fullName, :age, :role)');
            $stmt->execute([
                'userId' => $user->userId(),
                'userName' => $user->userName()->value(),
                'password' => $user->password(),
                'email' => $user->email()->value(),
                'fullName' => $user->fullName(),
                'age' => $user->age()->value(),
                'role' => $user->role(),
            ]);
    }

    public function findByUserName(UserName $username): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE user_name = :user_name');
        $stmt->execute(['user_name' => $username->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $this->mapRowToUser($row);
    }

    private function mapRowToUser(array $row): User
    {
        return new User(
            userName: new UserName($row['user_name']),
            password: $row['password'],
            email: $row['email'],
            fullName: $row['full_name'],
            age: new Age((int)$row['age']),
            role: $row['role'],
            userId: $row['user_id'],
            createdAt: new DateTime($row['created_at'])
        );
    }
}
