<?php

namespace App\Action\Admin;

use App\Domain\Repository\UserRepository;
use App\Service\SessionManagerInterface;

readonly class AdminLoginAction
{
    public function __construct(
        private UserRepository $userRepository,
        private SessionManagerInterface $sessionManager
    ) {}

    public function __invoke(string $userName, string $password): bool
    {
        $user = $this->userRepository->findByUserName($userName);
        if (!$user || !password_verify($password, $user->getPassword()) || $user->getRole() !== 'admin') {
            return false;
        }

        $this->sessionManager->startSession($user);
        return true;
    }
}