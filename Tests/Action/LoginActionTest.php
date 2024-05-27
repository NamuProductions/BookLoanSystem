<?php

namespace Action;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use App\Action\LoginAction;
use App\Domain\Repository\UserRepository;
use App\Service\SessionManagerInterface;
use App\Domain\Model\User;
use InvalidArgumentException;

class LoginActionTest extends TestCase
{
    private UserRepository $userRepository;
    private SessionManagerInterface $sessionManager;
    private LoginAction $sut;

    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->userRepository = $this->createMock(UserRepository::class);
            $this->sessionManager = $this->createMock(SessionManagerInterface::class);
        } catch (Exception) {
        }
        $this->sut = new LoginAction($this->userRepository, $this->sessionManager);
    }

    public function test_it_should_login_a_registered_user(): void
    {
        $userName = 'testUser';
        $password = 'testPassword';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $user = new User($userName, 'testUser@example.com', $passwordHash, 'user');

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

    public function test_it_should_login_an_admin(): void
    {
        $userName = 'adminUser';
        $password = 'adminPassword';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $user = new User($userName, 'admin@example.com', $passwordHash, 'admin');

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

    public function test_it_should_throw_exception_for_invalid_credentials(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid username or password.');

        $userName = 'testUser';
        $password = 'wrongPassword';

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName)
            ->willReturn(null);

        $this->sut->__invoke($userName, $password);
    }
}
