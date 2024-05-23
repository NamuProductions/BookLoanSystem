<?php

namespace App\Action\User;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;

readonly class RegisterUserAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(array $userData): void
    {
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address.');
        }

        $user = new User(
            $userData['userName'],
            $userData['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT)
        );

        $this->userRepository->save($user);
    }
}

