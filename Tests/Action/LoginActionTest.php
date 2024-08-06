<?php
declare(strict_types=1);

namespace Action;

use App\Action\LoginAction;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Domain\ValueObject\UserName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LoginActionTest extends TestCase
{
    private UserRepository $userRepository;
    private LoginAction $sut;

    public function test_it_should_login_a_registered_user(): void
    {
        $userName = new UserName('testUser');
        $password = 'testPassword1!';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $user = new User($userName->value(), $passwordHash, 'testUser@example.com', 'Test User One', 35, 'user');

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName)
            ->willReturn($user);

        $authenticatedUser = $this->sut->__invoke($userName, $password);

        $this->assertSame($user, $authenticatedUser);
    }

    public function test_it_should_login_an_admin(): void
    {
        $userName = new UserName('adminUser');
        $password = 'adminPassword1!';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $user = new User($userName->value(), $passwordHash, 'admin@example.com', 'Admin User', 34, 'admin');

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName)
            ->willReturn($user);

        $authenticatedUser = $this->sut->__invoke($userName, $password);

        $this->assertSame($user, $authenticatedUser);
    }

    public function test_it_should_throw_exception_for_invalid_credentials(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid username or password.');

        $userName = new UserName('testUser');
        $password = 'wrongPassword1!';

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName)
            ->willReturn(null);

        $this->sut->__invoke($userName, $password);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->sut = new LoginAction($this->userRepository);
    }
}
