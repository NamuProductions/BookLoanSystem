<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use InvalidArgumentException;

readonly class LoginAction
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function __invoke(string $userName, string $password): User
    {
        $user = $this->userRepository->findByUserName($userName);
        if (!$user || !password_verify($password, $user->password())) {
            throw new InvalidArgumentException('Invalid username or password.');
        }

        return $user;
    }
}
