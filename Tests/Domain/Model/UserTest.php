<?php
declare(strict_types=1);

namespace Domain\Model;

use App\Domain\Model\User;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserName;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $sut;

    public function test_it_should_return_user_name(): void
    {
        $this->assertSame('Ryan', $this->sut->userName()->value());
    }

    public function test_it_should_return_email(): void
    {
        $this->assertSame('ryan@example.com', $this->sut->email()->value());
    }

    public function test_it_should_return_password(): void
    {
        $this->assertSame('securePassword1!', $this->sut->password());
    }

    public function test_it_should_return_role(): void
    {
        $this->assertSame('admin', $this->sut->role());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $fixedUserId = '22222222-2222-2222-2222-222222222222';

        $userName = new UserName('Ryan');
        $age = new Age(19);

        $this->sut = new User(
            userName: $userName,
            password: new Password('securePassword1!'),
            email: new Email('ryan@example.com'),
            fullName: 'Ryan Martínez',
            age: $age,
            role: 'admin',
            userId: $fixedUserId,
        );
    }
}
