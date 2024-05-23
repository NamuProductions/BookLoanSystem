<?php

namespace Action\Admin;

use App\Action\Admin\AdminLoginAction;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class AdminLoginActionTest extends TestCase
{
    private AdminLoginAction $sut;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->userRepository = $this->createMock(UserRepository::class);
        } catch (Exception $e) {
        }
        $this->sut = new AdminLoginAction($this->userRepository);
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

        $result = $this->sut->__invoke($userName, $password);

        $this->assertTrue($result);
    }
}
