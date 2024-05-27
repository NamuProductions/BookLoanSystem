<?php

namespace Action\User;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use App\Action\User\LoginUserAction;
use App\Domain\Repository\UserRepository;
use App\Domain\Model\User;
use App\Service\SessionManagerInterface;

class LoginUserActionTest extends TestCase
{
    private LoginUserAction $sut;
    private UserRepository $userRepository;
    private SessionManagerInterface $sessionManager;

    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->userRepository = $this->createMock(UserRepository::class);
            $this->sessionManager = $this->createMock(SessionManagerInterface::class);
        } catch (Exception) {
        }
        $this->sut = new LoginUserAction($this->userRepository, $this->sessionManager);
    }

    public function test_it_should_login_a_registered_User(): void
    {
        $userName = 'testUser';
        $password = 'testPassword';
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $user = new User($userName, 'testUser@example.com', $password_hash, 'user');

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName)
            ->willReturn($user);

        $this->sessionManager
            ->expects($this->once())
            ->method('startSession')
            ->with($user);

        $this->sut->__invoke($userName, $password);
    }

    public function test_it_should_return_false_if_user_not_found(): void
    {
        $userName = 'notAUser';
        $password = 'password';

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName)
            ->willReturn(null);

        $this->sessionManager
            ->expects($this->never())
            ->method('startSession');

        $this->sut->__invoke($userName, $password);
    }

    public function test_it_should_return_false_if_password_is_incorrect(): void
    {
        $userName = 'testUser';
        $password = 'testPassword';
        $wrongPassword = 'wrongPassword';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $user = new User($userName, 'testUser@example.com', $passwordHash, 'user');

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName)
            ->willReturn($user);

        $this->sessionManager
            ->expects($this->never())
            ->method('startSession');

        $this->sut->__invoke($userName, $wrongPassword);
    }
}