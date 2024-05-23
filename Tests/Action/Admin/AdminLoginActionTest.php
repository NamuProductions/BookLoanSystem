<?php

namespace Tests\Action\Admin;

use App\Domain\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Tests\Action\AdminLoginAction;

class AdminLoginActionTest extends TestCase
{
    private AdminLoginAction $sut;
    private UserRepository $userRepository;
}
