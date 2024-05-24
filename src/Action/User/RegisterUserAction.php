<?php

namespace App\Action\User;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Service\SessionManager;
use InvalidArgumentException;

readonly class RegisterUserAction
{
    public function __construct(
        private UserRepository $userRepository,
        private SessionManager $sessionManager
    )
    {
    }

    public function __invoke(string $userName, string $email, string $password): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address.');
        }

        $user = new User(
            $userName,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            'user'
        );

        $this->userRepository->save($user);
        $this->sessionManager->startSession($user);
    }
}

