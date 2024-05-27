<?php

namespace App\Action;

use App\Domain\Repository\UserRepository;
use App\Service\SessionManagerInterface;
use InvalidArgumentException;

readonly class LoginAction
{
    public function __construct(
        private UserRepository $userRepository,
        private SessionManagerInterface $sessionManager
    ) {}

    public function __invoke(string $userName, string $password): void
    {
        $user = $this->userRepository->findByUserName($userName);

        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new InvalidArgumentException('Invalid username or password.');
        }

        $this->sessionManager->startSession($user);
    }
}
