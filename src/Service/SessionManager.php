<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Model\User;
use DateTime;

class SessionManager implements SessionManagerInterface
{
    public function startSession(User $user): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user'] = [
            'userId' => $user->userId(),
            'userName' => $user->userName(),
            'password' => $user->password(),
            'email' => $user->email(),
            'fullName' => $user->fullName(),
            'age' => $user->age(),
            'createdAt' => $user->createdAt()->format('c'),
            'role' => $user->role(),
        ];
    }

    public function endSession(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            unset($_SESSION['user']);
            session_destroy();
        }
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    public function getUser(): ?User
    {
        if (isset($_SESSION['user'])) {
            $userData = $_SESSION['user'];
            return new User(
                $userData['userName'],
                $userData['password'],
                $userData['email'],
                $userData['fullName'],
                $userData['age'],
                $userData['role'],
                $userData['userId'],
                new DateTime($userData['createdAt'])
            );
        }
        return null;
    }
}
