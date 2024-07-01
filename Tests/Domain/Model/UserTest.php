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
        $this->assertSame('Ryan M', $this->sut->getUserName());
    }

    public function test_it_should_return_email(): void
    {
        $this->assertSame('ryan@example.com', $this->sut->getEmail());
    }

    public function test_it_should_return_password(): void
    {
        $this->assertSame('securePassword', $this->sut->getPassword());
    }

    public function test_it_should_return_role(): void
    {
        $this->assertSame('admin', $this->sut->getRole());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new User('Ryan M', 'ryan@example.com', 'securePassword', 'admin');
    }
}
