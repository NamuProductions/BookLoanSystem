<?php

namespace App\Action\User;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Service\SessionManagerInterface;
use InvalidArgumentException;

readonly class RegisterUserAction
{
    public function __construct(
        private UserRepository          $userRepository,
        private SessionManagerInterface $sessionManager
    )
    {
    }

    public function __invoke(string $userName, string $email, string $password): void
    {
        if (empty($userName)) {
            throw new InvalidArgumentException('Username cannot be empty');
        }

        if (empty($password)) {
            throw new InvalidArgumentException('Password cannot be empty');
        }

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

