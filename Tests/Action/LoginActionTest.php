<?php
declare(strict_types=1);

namespace Action;

use App\Action\LoginAction;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\Password;
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
        $age = new Age(35);
        $password = new Password('testPassword1!');
        $passwordHash = password_hash($password->value(), PASSWORD_DEFAULT);
        $user = new User($userName, $passwordHash, 'testUser@example.com', 'Test User One', $age, 'user');

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName->value())
            ->willReturn($user);

        $authenticatedUser = $this->sut->__invoke($userName, $password);

        $this->assertSame($user, $authenticatedUser);
    }

    public function test_it_should_login_an_admin(): void
    {
        $userName = new UserName('adminUser');
        $age = new Age(34);
        $password = new Password('adminPassword1!');
        $passwordHash = password_hash($password->value(), PASSWORD_DEFAULT);
        $user = new User($userName, $passwordHash, 'admin@example.com', 'Admin User', $age, 'admin');

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName->value())
            ->willReturn($user);

        $authenticatedUser = $this->sut->__invoke($userName, $password);

        $this->assertSame($user, $authenticatedUser);
    }

    public function test_it_should_throw_exception_for_invalid_credentials(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid password.');

        $userName = new UserName('testUser');
        $password = new Password('wrongPassword1!');

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName->value())
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
