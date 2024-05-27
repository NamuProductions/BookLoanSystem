<?php

namespace App\Action\User;


use App\Domain\Repository\UserRepository;
use App\Service\SessionManagerInterface;

readonly class LoginUserAction
{
private UserRepository $userRepository;
private SessionManagerInterface $sessionManager;

    public function __construct(UserRepository $userRepository, SessionManagerInterface $sessionManager)
    {
        $this->userRepository = $userRepository;
        $this->sessionManager = $sessionManager;
    }

    public function __invoke(string $username, string $password): void
    {
        $user = $this->userRepository->findByUserName($username);

        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new \InvalidArgumentException('Invalid username or password');
        }

        $this->sessionManager->startSession($user);
    }
}