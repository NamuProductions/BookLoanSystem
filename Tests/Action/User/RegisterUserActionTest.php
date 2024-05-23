<?php

namespace Action\User;

use App\Action\User\RegisterUserAction;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RegisterUserAction::class)]
class RegisterUserActionTest extends TestCase
{
    private UserRepository $userRepository;
    private RegisterUserAction $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->sut = new RegisterUserAction($this->userRepository);
    }

    #[Test]
    public function it_should_Register_a_new_User(): void
    {
        $userData = [
            'username' => 'testUser',
            'email' => 'testuser@example.com',
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
