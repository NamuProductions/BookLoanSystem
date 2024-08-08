<?php

namespace Service;

use App\Domain\Model\User;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserName;
use App\Service\SessionManager;
use PHPUnit\Framework\TestCase;
use DateTime;

class SessionManagerTest extends TestCase
{
    private SessionManager $sut;
    private User $user;

    public function test_it_should_start_session()
    {
        $this->sut->startSession($this->user);

        $this->assertTrue($this->sut->isAuthenticated());
        $this->assertEquals($this->user, $this->sut->user());
    }

    public function test_it_should_end_session()
    {
        $this->sut->startSession($this->user);
        $this->sut->endSession();

        $this->assertFalse($this->sut->isAuthenticated());
        $this->assertNull($this->sut->user());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User(
            new UserName('testUser'),
            new Password('testPassword1!'),
            new Email('test@email.com'),
            'Test User',
            new Age(25),
            'user',
            null,
            new DateTime('2024-07-09T10:12:17.000000+0000')
        );
        $this->sut = new SessionManager();

    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
