<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Service\SessionManagerInterface;
use DateTime;
use InvalidArgumentException;

readonly class RegisterUserAction
{
    public function __construct(
        private UserRepository          $userRepository,
        private SessionManagerInterface $sessionManager
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
    ): void {
        $this->validateUserData($userName, $email, $password);

        $user = new User(
            $userName,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $fullName,
            $age,
            $role,
            $userId,
            $createdAt
        );

        $this->userRepository->save($user);

        $this->sessionManager->startSession($user);
    }

    private function validateUserData(string $userName, string $email, string $password): void
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
    }
}
