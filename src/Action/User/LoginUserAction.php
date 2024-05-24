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

    public function __invoke(string $username, string $password): bool
    {
        $user = $this->userRepository->findByUserName($username);
        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            return false;
        }

        $this->sessionManager->startSession($user);
        return true;
    }
}