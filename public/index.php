<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\BookController;
use App\Controller\UserController;
use App\Infrastructure\Persistence\PdoBookRepository;
use App\Infrastructure\Persistence\PdoUserRepository;

$pdo = new PDO('mysql:host=localhost;port=3307;dbname=library', 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$bookRepository = new PdoBookRepository($pdo);
$userRepository = new PdoUserRepository($pdo);

$bookController = new BookController($bookRepository, $userRepository);
$userController = new UserController($userRepository);

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/register' && $requestMethod === 'GET') {
    $userController->showRegistrationForm();
} elseif ($requestUri === '/register' && $requestMethod === 'POST') {
    $userController->register();
} elseif ($requestUri === '/login' && $requestMethod === 'GET') {
    $userController->showLoginForm();
} elseif ($requestUri === '/login' && $requestMethod === 'POST') {
    $userController->login();
} elseif ($requestUri === '/books' && $requestMethod === 'GET') {
    $bookController->index();
} elseif (preg_match('/^\/books\/(\d+)$/', $requestUri, $matches) && $requestMethod === 'GET') {
    $bookController->show((int)$matches[1]);
} elseif (preg_match('/^\/books\/(\d+)\/borrow$/', $requestUri, $matches) && $requestMethod === 'POST') {
    $bookController->borrow((int)$matches[1]);
} elseif (preg_match('/^\/books\/(\d+)\/return$/', $requestUri, $matches) && $requestMethod === 'POST') {
    $bookController->return((int)$matches[1]);
} else {
    http_response_code(404);
    echo "Page not found";
}

