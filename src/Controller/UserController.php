<?php

declare(strict_types=1);

namespace App\Controller;

use App\Action\User\RegisterUserAction;
use App\Action\LoginAction;
use App\Service\SessionManagerInterface;
use InvalidArgumentException;


class UserController
{
    private RegisterUserAction $registerUserAction;
    private LoginAction $loginAction;
    private SessionManagerInterface $sessionManager;

    public function __construct(
        RegisterUserAction $registerUserAction,
        LoginAction $loginAction,
        SessionManagerInterface $sessionManager
    ) {
        $this->registerUserAction = $registerUserAction;
        $this->loginAction = $loginAction;
        $this->sessionManager = $sessionManager;
    }

    public function showRegistrationForm(): Response
    {
        ob_start();
        require __DIR__ . '/../View/users/register.php';
        $body = ob_get_clean();
        return new Response($body);
    }

    public function register(): Response
    {
        $userName = $_POST['user_name'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $fullName = $_POST['full_name'];
        $age = $_POST['age'];

        try {
            $user = $this->registerUserAction->__invoke($userName, $email, $password, $fullName, $age);
            $this->sessionManager->startSession($user);
            error_log("User ID stored in session: " . $_SESSION['user']['userId']);
            return new redirectResponse('/books');
        } catch (InvalidArgumentException $e) {
            error_log($e->getMessage());
            return new redirectResponse('/register');
        }
    }

    public function showLoginForm(): Response
    {
        ob_start();
        require __DIR__ . '/../View/users/login.php';
        $body = ob_get_clean();
        return new Response($body);
    }

    public function login(): Response
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            $user = $this->loginAction->__invoke($username, $password);
            $this->sessionManager->startSession($user);
            error_log("User ID stored in session: " . $_SESSION['user']['userId']);
            return new redirectResponse('/books');
        } catch (InvalidArgumentException $e) {
            error_log($e->getMessage());
            return new redirectResponse('/login');
        }
    }

    public function logout(): Response
    {
        $this->sessionManager->destroy();
        return new RedirectResponse('/login');
    }
}
