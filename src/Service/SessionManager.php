<?php

namespace App\Service;

use App\Domain\Model\User;

class SessionManager implements SessionManagerInterface
{
    public function startSession(User $user): void
    {
        session_start();
        $_SESSION['user'] = [
            'username' => $user->getUserName(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
        ];
    }

    public function endSession(): void
    {
        session_destroy();
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    public function getUser(): ?User
    {
        if (isset($_SESSION['user'])) {
            $userData = $_SESSION['user'];
            return new User($userData['username'], $userData['email'], '', $userData['role']);
        }
        return null;
    }
}
