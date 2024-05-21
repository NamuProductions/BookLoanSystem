<?php

namespace Action\User;

use PHPUnit\Framework\TestCase;
use App\Action\User\LoginUserAction;
use App\Domain\Repository\UserRepository;
use App\Domain\Model\User;

class LoginUserActionTest extends TestCase
{
    private LoginUserAction $sut;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->sut = new LoginUserAction($this->userRepository);
    }

    public function it_should_login_a_registered_User(): void
    {
        $userName = 'testUser';
        $password = 'testPassword';
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $user = new User($userName, 'testUser@example.com', $password_hash);

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName)
            ->willReturn($user);

        $result = $this->sut->__invoke($userName, $password);

        $this->assertTrue($result);
    }

}