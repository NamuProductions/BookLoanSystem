<?php

namespace Action\User;

use PHPUnit\Framework\TestCase;
use App\Action\User\LoginUserAction;
use App\Domain\Repository\UserRepository;


class LoginUserActionTest extends TestCase
{
    private LoginUserAction $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $userRepository = $this->createMock(UserRepository::class);
        $this->sut = new LoginUserAction($userRepository);
    }

}