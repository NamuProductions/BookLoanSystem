<?php

namespace Action\User;

use App\Service\SessionManager;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use App\Action\User\RegisterUserAction;
use App\Domain\Repository\UserRepository;
use App\Domain\Model\User;

class RegisterUserActionTest extends TestCase
{
    private UserRepository $userRepository;
    private RegisterUserAction $sut;
    private SessionManager $sessionManager;

    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->userRepository = $this->createMock(UserRepository::class);
            $this->sessionManager = $this->createMock(SessionManager::class);
        } catch (Exception) {
        }
        $this->sut = new RegisterUserAction($this->userRepository, $this->sessionManager);
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

        $this->sessionManager
            ->expects($this->once())
            ->method('startSession')
            ->with($this->callback(function (User $user) use ($userName, $email) {
                return $user->getUserName() === $userName &&
                    $user->getEmail() === $email;
            }));

        $this->sut->__invoke($userName, $email, $password);
    }

    public function test_it_should_throw_exception_for_invalid_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address.');

        $userName = 'testUser';
        $invalidEmail = 'invalid-email';
        $password = 'testPassword';

        $this->sut->__invoke($userName, $invalidEmail, $password);
    }

}
