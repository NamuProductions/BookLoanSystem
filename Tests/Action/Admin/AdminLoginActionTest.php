<?php

namespace Tests\Action\Admin;

use App\Domain\Repository\UserRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Tests\Action\AdminLoginAction;

class AdminLoginActionTest extends TestCase
{
    private AdminLoginAction $sut;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->userRepository = $this->createMock(UserRepository::class);
        } catch (Exception $e) {
        }
        $this->sut = new AdminLoginAction($this->userRepository);
    }

}
