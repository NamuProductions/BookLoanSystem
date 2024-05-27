<?php

namespace App\Action\Admin;

use App\Domain\Repository\UserRepository;
use App\Service\SessionManagerInterface;
use InvalidArgumentException;

readonly class AdminLoginAction
{
    public function __construct(
        private UserRepository $userRepository,
        private SessionManagerInterface $sessionManager
    ) {}

    public function __invoke(string $userName, string $password): void
    {
        $user = $this->userRepository->findByUserName($userName);

        if (!$user || !password_verify($password, $user->getPassword()) || $user->getRole() !== 'admin') {
            throw new InvalidArgumentException('Invalid username or password');
        }

        $this->sessionManager->startSession($user);
    }
}