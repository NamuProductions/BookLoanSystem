<?php

declare(strict_types=1);

namespace Action\User;

use App\Action\User\RegisterUserAction;
use App\Domain\Model\User;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserName;
use App\Domain\Repository\UserRepository;
use App\Exception\ValidationException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RegisterUserActionTest extends TestCase
{
    private UserRepository $userRepository;
    private RegisterUserAction $sut;

    public function test_it_should_register_a_user(): void
    {
        $userName = new UserName('testUser');
        $email = new Email('correct@email.com');
        $password = new Password('TestPassword1!');
        $fullName = 'Test User';
        $age = new Age(30);

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (User $user) use ($userName, $email, $password, $fullName, $age) {
                return $user->userName()->value() === $userName->value() &&
                    $user->email() &&
                    $user->password() &&
                    $user->fullName() === $fullName &&
                    $user->age()->value() === $age->value();
            }));

        $user = $this->sut->__invoke($userName, $email, $password, $fullName, $age);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userName->value(), $user->userName()->value());
        $this->assertEquals($email->value(), $user->email()->value());
        $this->assertEquals($fullName, $user->fullName());
        $this->assertEquals($age->value(), $user->age()->value());
    }

    public function test_it_should_throw_exception_for_invalid_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address.');

        $invalidEmail = 'invalid-email';

        new Email($invalidEmail);
    }

    public function test_it_should_throw_exception_for_invalid_user_data(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Username cannot be empty');

        $userName = new UserName('');
        $email = new Email('correct@email.com');
        $password = new Password('TestPassword1!');
        $fullName = 'Test User';
        $age = new Age(30);

        $this->sut->__invoke($userName, $email, $password, $fullName, $age);
    }

    public function test_it_should_throw_exception_for_invalid_password_data(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 8 characters long.');

        $userName = new UserName('CorrectUserName');
        $email = new Email('correct@email.com');
        $password = new Password('1');
        $fullName = 'Test User';
        $age = new Age(30);

        $this->sut->__invoke($userName, $email, $password, $fullName, $age);
    }

    public function test_it_should_throw_exception_for_trying_to_register_new_user_data_with_equal_userName(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation errors occurred');

        $userName = new UserName('existingUser');
        $email = new Email('correct@email.com');
        $password = new Password('TestPassword1!');
        $fullName = 'Test User';
        $age = new Age(30);

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->willReturn(new User(
                new UserName('existingUser'),
                $password,
                $email,
                $fullName,
                $age
            ));

        $this->sut->__invoke($userName, $email, $password, $fullName, $age);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->sut = new RegisterUserAction($this->userRepository);
    }
}
