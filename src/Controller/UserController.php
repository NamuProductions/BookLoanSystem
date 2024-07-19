<?php

namespace App\Controller;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;

class UserController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function showRegistrationForm(): void
    {
        require __DIR__ . '/../View/users/register.php';
    }

    #[NoReturn] public function register(): void
    {
        $userName = $_POST['user_name'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $email = $_POST['email'];
        $fullName = $_POST['full_name'];
        $age = $_POST['age'];

        $user = new User($userName, $password, $email, $fullName, $age, 'user');
        $this->userRepository->save($user);

        session_start();
        $_SESSION['userId'] = $user->userId();
        error_log("User ID stored in session after registration: " . $_SESSION['userId']);

        header('Location: /books');
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
