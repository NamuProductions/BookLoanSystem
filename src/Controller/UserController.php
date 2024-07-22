<?php

namespace App\Controller;

use App\Action\LoginAction;
use App\Action\User\RegisterUserAction;
use InvalidArgumentException;
use JetBrains\PhpStorm\NoReturn;

class UserController
{
    private RegisterUserAction $registerUserAction;
    private LoginAction $loginAction;

    public function __construct(RegisterUserAction $registerUserAction, LoginAction $loginAction)
    {
        $this->registerUserAction = $registerUserAction;
        $this->loginAction = $loginAction;
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
            $user = $this->registerUserAction->__invoke($userName, $email, $password, $fullName, $age);

            session_start();
            $_SESSION['userId'] = $user->userId();
            error_log("User ID stored in session: " . $_SESSION['userId']);
            header('Location: /books');
        } catch (InvalidArgumentException $e) {
            error_log($e->getMessage());
            header('Location: /register');
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

        try {
            $user = $this->loginAction->__invoke($username, $password);
            session_start();
            $_SESSION['userId'] = $user->userId();
            error_log("User ID stored in session: " . $_SESSION['userId']);
            header('Location: /books');
        } catch (InvalidArgumentException $e) {
            error_log($e->getMessage());
            header('Location: /login');
        }
    }
}