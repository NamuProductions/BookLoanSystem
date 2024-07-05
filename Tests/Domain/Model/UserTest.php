<?php
declare(strict_types=1);

namespace Domain\Model;

use App\Domain\Model\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $sut;

    public function test_it_should_return_user_name(): void
    {
        $this->assertSame('Ryan M', $this->sut->userName());
    }

    public function test_it_should_return_email(): void
    {
        $this->assertSame('ryan@example.com', $this->sut->email());
    }

    public function test_it_should_return_password(): void
    {
        $this->assertSame('securePassword', $this->sut->password());
    }

    public function test_it_should_return_role(): void
    {
        $this->assertSame('admin', $this->sut->role());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new User(
            userName: 'Ryan M',
            password: 'securePassword',
            email: 'ryan@example.com',
            role: 'admin'
        );
    }
}
