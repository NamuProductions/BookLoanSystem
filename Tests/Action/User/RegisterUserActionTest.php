<?php

declare(strict_types=1);

namespace Action\User;

use App\Action\User\RegisterUserAction;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RegisterUserActionTest extends TestCase
{
    private UserRepository $userRepository;
    private RegisterUserAction $sut;
    private string $fixedUserId;

    public function test_it_should_register_a_user(): void
    {
        $userName = 'testUser';
        $email = 'correct@email.com';
        $password = 'testPassword1!';

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (User $user) use ($userName, $email, $password) {
                return $user->userName() === $userName &&
                    $user->email() === $email &&
                    password_verify($password, $user->password());
            }));

        $user = $this->sut->__invoke($userName, $email, $password);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userName, $user->userName());
        $this->assertEquals($email, $user->email());
    }

    public function test_it_should_throw_exception_for_invalid_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address.');

        $userName = 'testUser';
        $invalidEmail = 'invalid-email';
        $password = 'testPassword1!';

        $this->sut->__invoke($userName, $invalidEmail, $password);
    }

    public function test_it_should_throw_exception_for_invalid_user_data(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Username cannot be empty');

        $userName = '';
        $email = 'correct@email.com';
        $password = 'correctPassword1!';

        $this->sut->__invoke($userName, $email, $password);
    }

    public function test_it_should_throw_exception_for_invalid_password_data(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one digit, and one special character.');

        $userName = 'testUser';
        $email = 'correct@email.com';
        $password = '1';

        $this->sut->__invoke($userName, $email, $password);
    }

    public function test_it_should_throw_exception_for_trying_to_register_new_user_data_with_equal_userName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Username already exists');

        $userName = 'existingUser';
        $email = 'correct@email.com';
        $password = 'correctPassword1!';

        $existingUser = new User(
            $userName,
            password_hash($password, PASSWORD_DEFAULT),
            $email,
            'fullName',
            25,
            'user',
            $this->fixedUserId,
            new DateTime(),
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName)
            ->willReturn($existingUser);

        $this->sut->__invoke($userName, $email, $password);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixedUserId = '22222222-2222-2222-2222-222222222222';

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->sut = new RegisterUserAction($this->userRepository);
    }
}
