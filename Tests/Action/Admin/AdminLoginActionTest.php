<?php

namespace Action\Admin;

use App\Action\Admin\AdminLoginAction;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Service\SessionManagerInterface;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class AdminLoginActionTest extends TestCase
{
    private AdminLoginAction $sut;
    private SessionManagerInterface $sessionManager;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->userRepository = $this->createMock(UserRepository::class);
            $this->sessionManager = $this->createMock(SessionManagerInterface::class);
        } catch (Exception) {
        }
        $this->sut = new AdminLoginAction($this->userRepository, $this->sessionManager);
    }

    public function test_it_should_login_an_admin_user(): void
    {
        $userName = 'adminUser';
        $password = 'adminPassword';
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $user = new User($userName, 'admin@hotmail.com', $password_hash, 'admin');

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

    public function test_it_should_throw_exception_if_credentials_are_invalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Username or password');

        $userName = 'adminUser';
        $password = 'wrongPassword';

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName)
            ->willReturn(null);

        $this->sut->__invoke($userName, $password);
    }
}
