<?php

namespace App\Action\User;


use App\Domain\Repository\UserRepository;

class LoginUserAction
{

    public function __construct(private UserRepository $userRepository)
    {

    }

    public function __invoke(string $username, string $password): bool
    {
        $user = $this->userRepository->findByUserName($username);
        if (!$user) {
            return false;
        }
        return password_verify($password, $user->getPassword());
    }
}