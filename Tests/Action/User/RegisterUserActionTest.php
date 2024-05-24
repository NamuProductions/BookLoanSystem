<?php

namespace Action\User;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use App\Action\User\RegisterUserAction;
use App\Domain\Repository\UserRepository;
use App\Domain\Model\User;

class RegisterUserActionTest extends TestCase
{
    private UserRepository $userRepository;
    private RegisterUserAction $sut;

    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->userRepository = $this->createMock(UserRepository::class);
        } catch (Exception) {
        }
        $this->sut = new RegisterUserAction($this->userRepository);
    }

    public function test_it_should_register_a_user(): void
    {
            $userName = 'testUser';
            $email = 'correct@email.com';
            $password = 'testPassword';

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (User $user) use ($userName, $email, $password) {
                return $user->getUserName() === $userName &&
                    $user->getEmail() === $email &&
                    password_verify($password, $user->getPassword());
            }));

        $this->sut->__invoke($userName, $email, $password);
    }
}
