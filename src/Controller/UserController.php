<?php

namespace App\Controller;

use App\Action\User\RegisterUserAction;
use App\Domain\Repository\UserRepository;
use InvalidArgumentException;
use JetBrains\PhpStorm\NoReturn;

class UserController
{
    private UserRepository $userRepository;
    private RegisterUserAction $registerUserAction;

    public function __construct(UserRepository $userRepository, RegisterUserAction $registerUserAction)
    {
        $this->userRepository = $userRepository;
        $this->registerUserAction = $registerUserAction;
    }

    public function showRegistrationForm(): void
    {
        require __DIR__ . '/../View/users/register.php';
    }

    #[NoReturn] public function register(): void
    {
        $userName = $_POST['user_name'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $fullName = $_POST['full_name'];
        $age = $_POST['age'];

        try {
            $this->registerUserAction->__invoke($userName, $email, $password, $fullName, $age);
            header('Location: /books');
        } catch (InvalidArgumentException $e) {
            error_log($e->getMessage());
            header('Location: /books');
        }
    }

    public function showLoginForm(): void
    {
        require __DIR__ . '/../View/users/login.php';
    }

    #[NoReturn] public function login(): void
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = $this->userRepository->findByUserName($username);
        if ($user && password_verify($password, $user->password())) {
            session_start();
            $_SESSION['userId'] = $user->userId();
            error_log("User ID stored in session: " . $_SESSION['userId']);
            header('Location: /books');
        } else {
            error_log("Login failed for user: " . $username);
            header('Location: /login');
        }
    }
}
