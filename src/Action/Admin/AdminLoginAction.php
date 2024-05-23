<?php

namespace App\Action\Admin;

use App\Domain\Repository\UserRepository;

readonly class AdminLoginAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(string $userName, string $password): bool
    {
        $user = $this->userRepository->findByUserName($userName);
        if (!$user || $user->getRole() !== 'admin') {
            return false;
        }

        return password_verify($password, $user->getPassword());
    }
}