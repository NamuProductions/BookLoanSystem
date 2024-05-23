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
        } catch (Exception $e) {
        }
        $this->sut = new RegisterUserAction($this->userRepository);
    }

    public function test_it_should_Register_a_new_User(): void
    {
        $userData = [
            'username' => 'testUser',
            'email' => 'testUser@example.com',
            'password' => 'testPassword'
        ];

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (User $user) use ($userData) {
                return $user->getUserName() === $userData['username'] &&
                    $user->getEmail() === $userData['email'] &&
                    password_verify($userData['password'], $user->getPassword());
            }));

        $this->sut->__invoke($userData);
    }
}
