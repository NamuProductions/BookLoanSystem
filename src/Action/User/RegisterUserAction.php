<?php

declare(strict_types=1);

namespace App\Action\User;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Util\UUID;
use DateTime;
use InvalidArgumentException;

readonly class RegisterUserAction
{
    public function __construct(
        private UserRepository          $userRepository,
    )
    {
    }

    public function __invoke(
        string $userName,
        string $email,
        string $password,
        ?string $fullName = null,
        ?int $age = null,
        ?string $role = 'user',
        ?string $userId = null,
        ?DateTime $createdAt = null
    ): User {
        $this->validateUserData($userName, $email, $password);

        if ($this->userRepository->findByUserName($userName)) {
            throw new InvalidArgumentException('Username already exists.');
        }

        $user = new User(
            $userName,
            password_hash($password, PASSWORD_DEFAULT),
            $email,
            $fullName,
            $age,
            $role,
            $userId ?? UUID::generate(),
            $createdAt ?? new DateTime()
        );

        $this->userRepository->save($user);

        return $user;
    }

    private function validateUserData(string $userName, string $email, string $password): void
    {
        if (empty($userName)) {
            throw new InvalidArgumentException('Username cannot be empty');
        }

        if (empty($password) || !$this->isValidPassword($password)) {
            throw new InvalidArgumentException('Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one digit, and one special character.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^.+@[^-][A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $email)) {
            throw new InvalidArgumentException('Invalid email address.');
        }
    }

    private function isValidPassword(string $password): bool
    {
        return strlen($password) >= 8 && preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password);
    }
}
