<?php

namespace Service;

use App\Domain\Model\User;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\UserName;
use App\Service\SessionManager;
use PHPUnit\Framework\TestCase;
use DateTime;

class SessionManagerTest extends TestCase
{
    private SessionManager $sut;

    public function test_it_should_start_session()
    {
        $createdAt = new DateTime('2024-07-09T10:12:17.000000+0000');
        $userName = new UserName('testUser');
        $age = new Age(25);

        $user = new User(
            $userName,
            'testPassword',
            'test@email.com',
            'Test User',
            $age,
            'user',
            null,
            $createdAt
        );

        $this->sut->startSession($user);

        $this->assertTrue($this->sut->isAuthenticated());
        $this->assertEquals($user, $this->sut->getUser());
    }

    public function test_it_should_end_session()
    {
        $createdAt = new DateTime('2024-07-09T10:12:17.000000+0000');
        $userName = new UserName('testUser');
        $age = new Age(25);

        $user = new User(
            $userName,
            'testPassword',
            'test@email.com',
            'Test User',
            $age,
            'user',
            null,
            $createdAt
        );

        $this->sut->startSession($user);
        $this->sut->endSession();

        $this->assertFalse($this->sut->isAuthenticated());
        $this->assertNull($this->sut->getUser());
    }

    protected function setUp(): void
    {
        parent::setUp();
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
