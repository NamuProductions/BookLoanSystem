<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserName;
use InvalidArgumentException;

readonly class LoginAction
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function __invoke(UserName $userName, Password $password): User
    {
        $user = $this->userRepository->findByUserName($userName);

        if (!$user) {
            throw new InvalidArgumentException('Invalid username.');
        }

        if (!password_verify($password->value(), $user->password())) {
            throw new InvalidArgumentException('Incorrect password');
        }

        return $user;
    }
}
