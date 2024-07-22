<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Action\LoginAction;
use App\Controller\BookController;
use App\Controller\UserController;
use App\Infrastructure\Persistence\PdoBookRepository;
use App\Infrastructure\Persistence\PdoUserRepository;
use App\Action\User\RegisterUserAction;
use App\Service\SessionManager;

$pdo = new PDO('mysql:host=localhost;port=3307;dbname=library', 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$bookRepository = new PdoBookRepository($pdo);
$userRepository = new PdoUserRepository($pdo);

$sessionManager = new SessionManager();
$registerUserAction = new RegisterUserAction($userRepository);
$loginAction = new LoginAction($userRepository);

$bookController = new BookController($bookRepository, $sessionManager);
$userController = new UserController($registerUserAction, $loginAction, $sessionManager);


$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$basePath = '/BookLoanSystem/public';
if (str_starts_with($requestUri, $basePath)) {
    $requestUri = substr($requestUri, strlen($basePath));
}

$routes = [
    '/^\/$/' => [
        "GET" => function() {
            include __DIR__ . '/../src/View/home.php';
        },
    ],
    '/^\/register$/' => [
        "GET" => [$userController, 'showRegistrationForm'],
        "POST" => [$userController, 'register'],
    ],
    '/^\/login$/' => [
        "GET" => [$userController, 'showLoginForm'],
        "POST" => [$userController, 'login'],
    ],
    '/^\/books$/' => [
        "GET" => [$bookController, 'index'],
    ],
    '/^\/books\/(\d+)$/' => [
        "GET" => [$bookController, 'show'],
    ],
    '/^\/books\/(\d+)\/borrow$/' => [
        "POST" => [$bookController, 'borrow'],
    ],
    '/^\/books\/(\d+)\/return$/' => [
        "POST" => [$bookController, 'return'],
    ],
    '/^\/logout$/' => [
        "GET" => function() {
            session_destroy();
            header('Location: /');
            }
    ],
];

$routeFound = false;
foreach ($routes as $route => $routeConfig) {
    if (preg_match($route, $requestUri, $matches)) {
        foreach ($routeConfig as $method => $action) {
            if ($requestMethod === $method) {
                array_shift($matches);
                call_user_func($action, ...$matches);
                $routeFound = true;
                break 2;
            }
        }
    }
}

if (!$routeFound) {
    http_response_code(404);
    echo "Page not found";
}
