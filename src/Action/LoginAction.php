<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Domain\ValueObject\UserName;
use InvalidArgumentException;

readonly class LoginAction
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function __invoke(UserName $userName, string $password): User
    {
        $user = $this->userRepository->findByUserName($userName->value());

        if (!$user) {
            throw new InvalidArgumentException('Invalid username.');
        }

        if (!password_verify($password, $user->password())) {
            throw new InvalidArgumentException('Incorrect password');
        }

        return $user;
    }
}
