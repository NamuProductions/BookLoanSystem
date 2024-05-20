<?php
namespace App\Action\User;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;

class RegisterUserAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(array $userData): void
    {
        $user = new User(
            $userData['username'],
            $userData['email'],
            $userData['password']
        );

        $this->userRepository->save($user);
    }
}

