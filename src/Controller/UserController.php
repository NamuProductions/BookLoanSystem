<?php

declare(strict_types=1);

namespace App\Controller;

use App\Action\User\RegisterUserAction;
use App\Action\LoginAction;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserName;
use App\Service\SessionManagerInterface;
use App\Exception\ValidationException;
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

    public function showRegistrationForm(array $errors = [], array $oldValues = []): Response
    {
        ob_start();
        require __DIR__ . '/../View/users/register.php';
        $body = ob_get_clean();
        return new Response($body);
    }

    public function register(array $errors = [], array $oldValues = []): Response
    {
        $errors = [];
        $oldValues = [
            'user_name' => $_POST['user_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'full_name' => $_POST['full_name'] ?? '',
            'age' => $_POST['age'] ?? ''
        ];

        $userName = null;
        $password = null;
        $email = null;
        $fullName = $_POST['full_name'] ?? '';
        $age = null;

        // Validar nombre de usuario
        try {
            $userName = new UserName($_POST['user_name'] ?? '');
        } catch (InvalidArgumentException $e) {
            $errors[] = $e->getMessage();
        }

        // Validar contraseña
        try {
            $password = new Password($_POST['password'] ?? '');
        } catch (InvalidArgumentException $e) {
            $errors[] = $e->getMessage();
        }

        // Validar email
        try {
            $email = new Email($_POST['email'] ?? '');
        } catch (InvalidArgumentException $e) {
            $errors[] = $e->getMessage();
        }

        // Validar edad
        if (isset($_POST['age'])) {
            try {
                $age = new Age((int) $_POST['age']);
            } catch (InvalidArgumentException $e) {
                $errors[] = $e->getMessage();
            }
        }

        // Si hay errores, mostrar el formulario con los errores y valores antiguos
        if (!empty($errors)) {
            return $this->showRegistrationForm($errors, $oldValues);
        }

        // Verificar que $userName no sea null antes de continuar
        if ($userName === null) {
            $errors[] = 'Invalid username.';
            return $this->showRegistrationForm($errors, $oldValues);
        }

        // Intentar registrar al usuario
        try {
            $user = $this->registerUserAction->__invoke(
                $userName,
                $email,
                $password,
                $fullName,
                $age
            );
            $this->sessionManager->startSession($user);
            error_log("User ID stored in session: " . $_SESSION['user']['userId']);
            return new RedirectResponse('/books');
        } catch (ValidationException $e) {
            return $this->showRegistrationForm($e->getErrors(), $oldValues);
        } catch (InvalidArgumentException $e) {
            return $this->showRegistrationForm([$e->getMessage()], $oldValues);
        }
    }

    public function showLoginForm(array $errors = [], array $oldValues = []): Response
    {
        ob_start();
        require __DIR__ . '/../View/users/login.php'; // Pasar errores y valores antiguos a la vista.
        $body = ob_get_clean();
        return new Response($body);
    }

    public function login(): Response
    {
        $errors = [];
        $oldValues = ['username' => $_POST['username'] ?? ''];

        $username = null;
        $password = $_POST['password'] ?? '';

        // Validar nombre de usuario
        try {
            $username = new UserName($_POST['username'] ?? '');
        } catch (InvalidArgumentException $e) {
            $errors[] = $e->getMessage();
        }

        // Validar contraseña
        if (empty($password)) {
            $errors[] = 'Password cannot be empty.';
        }

        // Si hay errores, mostrar el formulario de inicio de sesión con errores y valores antiguos
        if (!empty($errors)) {
            return $this->showLoginForm($errors, $oldValues);
        }

        // Verificar que $username no sea null antes de continuar
        if ($username === null) {
            $errors[] = 'Invalid username.';
            return $this->showLoginForm($errors, $oldValues);
        }

        // Intentar iniciar sesión
        try {
            $user = $this->loginAction->__invoke($username, $password);
            $this->sessionManager->startSession($user);
            error_log("User ID stored in session: " . $_SESSION['user']['userId']);
            return new RedirectResponse('/books');
        } catch (InvalidArgumentException $e) {
            return $this->showLoginForm([$e->getMessage()], $oldValues);
        }
    }

    public function logout(): Response
    {
        $this->sessionManager->endSession();
        return new RedirectResponse('/login');
    }
}
