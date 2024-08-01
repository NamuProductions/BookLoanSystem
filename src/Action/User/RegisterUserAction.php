<?php

declare(strict_types=1);

namespace App\Action\User;

use App\Domain\Model\User;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserName;
use App\Domain\Repository\UserRepository;
use App\Exception\ValidationException;
use App\Util\UUID;
use DateTime;
use InvalidArgumentException;

readonly class RegisterUserAction
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {
    }

    public function __invoke(
        UserName $userName,
        string $email,
        string $password,
        ?string $fullName = null,
        ?Age $age = null,
        ?string $role = 'user',
        ?string $userId = null,
        ?DateTime $createdAt = null
    ): User {
        $errors = $this->validateUserData($userName, $email, $password);

        if ($this->userRepository->findByUserName($userName->value())) {
            $errors[] = 'Username already exists.';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        $passwordValueObject = new Password($password);

        $user = new User(
            $userName->value(),
            password_hash($passwordValueObject->getValue(), PASSWORD_DEFAULT),
            $email,
            $fullName,
            $age->value(),
            $role,
            $userId ?? UUID::generate(),
            $createdAt ?? new DateTime(),
        );

        $this->userRepository->save($user);

        return $user;
    }

    private function validateUserData(UserName $userName, string $email, string $password): array
    {
        $errors = [];

        if (empty($userName->value())) {
            $errors[] = 'Username cannot be empty';
        } else {
            try {
                new UserName($userName->value());
            } catch (InvalidArgumentException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^.+@[^-][A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $email)) {
            $errors[] = 'Invalid email address.';
        }

        try {
            new Password($password);
        } catch (InvalidArgumentException $e) {
            $errors[] = $e->getMessage();
        }

        return $errors;
    }
}
