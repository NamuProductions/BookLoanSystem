<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Model\User;
use App\Domain\ValueObject\UserName;
use App\Domain\ValueObject\Age; // Asegúrate de incluir el valor del objeto Age
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
            'userName' => $user->userName()->value(),
            'password' => $user->password(),
            'email' => $user->email(),
            'fullName' => $user->fullName(),
            'age' => $user->age()->value(),
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
                new UserName($userData['userName']),
                $userData['password'],
                $userData['email'],
                $userData['fullName'],
                new Age((int)$userData['age']),
                $userData['role'],
                $userData['userId'],
                new DateTime($userData['createdAt'])
            );
        }
        return null;
    }
}
