<?php

declare(strict_types=1);

namespace App\Action\User;

use App\Domain\Model\User;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserName;
use App\Domain\Repository\UserRepository;
use App\Exception\ValidationException;
use App\Util\UUID;
use DateTime;

readonly class RegisterUserAction
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function __invoke(
        UserName $userName,
        Email $email,
        Password $password,
        string $fullName,
        Age $age,
        ?string $role = 'user',
        ?string $userId = null,
        ?DateTime $createdAt = null
    ): User {
        $errors = $this->validateUserData($userName);

        if ($this->userRepository->findByUserName($userName)) {
            $errors[] = 'Username already exists.';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        $hashedPassword = password_hash($password->value(), PASSWORD_DEFAULT);

        $user = new User(
            $userName,
            new Password($hashedPassword),
            $email,
            $fullName,
            $age,
            $role,
            $userId ?? UUID::generate(),
            $createdAt ?? new DateTime(),
        );

        $this->userRepository->save($user);

        return $user;
    }

    private function validateUserData(UserName $userName): array
    {
        $errors = [];

        if (empty($userName->value())) {
            $errors[] = 'Username cannot be empty';
        }


        return $errors;
    }
}
