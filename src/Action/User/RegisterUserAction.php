<?php

declare(strict_types=1);

namespace App\Action\User;

use App\Domain\Model\User;
use App\Domain\ValueObject\Password;
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
        $age = !empty($age) ? $age : null;

        $this->validateUserData($userName, $email, $password);

        if ($this->userRepository->findByUserName($userName)) {
            throw new InvalidArgumentException('Username already exists.');
        }

        $passwordValueObject = new Password($password);

        $user = new User(
            $userName,
            password_hash($passwordValueObject->getValue(), PASSWORD_DEFAULT),
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

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^.+@[^-][A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $email)) {
            throw new InvalidArgumentException('Invalid email address.');
        }
        new Password($password);
    }


}
