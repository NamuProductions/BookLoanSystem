<?php

namespace Service;

use App\Domain\Model\User;
use App\Service\SessionManager;
use PHPUnit\Framework\TestCase;

class SessionManagerTest extends TestCase
{
    private SessionManager $sut;

    public function test_it_should_start_session()
    {
        session_start();

        $user = new User('testUser', 'test@email.com', 'testPassword', 'user');

        $this->sut->startSession($user);

        $this->assertTrue($this->sut->isAuthenticated());
        $this->assertEquals($user, $this->sut->getUser());
    }

    public function test_it_should_end_session()
    {
        session_start();

        $user = new User('testUser', 'test@email.com', 'testPassword', 'user');

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