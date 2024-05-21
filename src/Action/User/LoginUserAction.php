<?php

namespace App\Action\User;


use App\Domain\Repository\UserRepository;

readonly class LoginUserAction
{

    public function __construct(private UserRepository $userRepository)
    {

    }


}