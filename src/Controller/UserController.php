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
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $user = new User($username, $email, $password, 'user');
        $this->userRepository->save($user);

        header('Location: /login');
        exit;
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
        if ($user && password_verify($password, $user->getPassword())) {
            session_start();
            $_SESSION['user'] = $username;
            header('Location: /books');
        } else {
            header('Location: /login');
        }
        exit;
    }
}
