<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Action\Admin\LoanRequestsAction;
use App\Action\LoginAction;
use App\Action\User\ListAvailableBooksAction;
use App\Action\User\ListUserLoansAction;
use App\Action\User\RegisterUserAction;
use App\Action\User\SearchBooksAction;
use App\Action\Admin\AddNewBookAction;
use App\Action\User\MarkBookAsReturnedAction;
use App\Action\User\RequestBookLoanAction;
use App\Controller\BookController;
use App\Controller\LoanController;
use App\Controller\Response;
use App\Controller\UserController;
use App\Infrastructure\Persistence\PdoBookRepository;
use App\Infrastructure\Persistence\PdoUserRepository;
use App\Service\SessionManager;
use App\Service\LoanRequestQueryService;

$pdo = new PDO('mysql:host=localhost;port=3307;dbname=library', 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$bookRepository = new PdoBookRepository($pdo);
$userRepository = new PdoUserRepository($pdo);

$sessionManager = new SessionManager();
$registerUserAction = new RegisterUserAction($userRepository);
$loginAction = new LoginAction($userRepository);
$requestBookLoanAction = new RequestBookLoanAction($bookRepository, $userRepository);
$markBookAsReturnedAction = new MarkBookAsReturnedAction($bookRepository);
$searchBooksAction = new SearchBooksAction($bookRepository);
$addNewBookAction = new AddNewBookAction($bookRepository);
$listAvailableBooksAction = new ListAvailableBooksAction($bookRepository);

$loanRequestQueryService = new LoanRequestQueryService($bookRepository);
$loanRequestsAction = new LoanRequestsAction($loanRequestQueryService);
$listUserLoansAction = new ListUserLoansAction($bookRepository);

$bookController = new BookController(
    $bookRepository,
    $sessionManager,
    $addNewBookAction,
    $listAvailableBooksAction,
    $searchBooksAction,
    $requestBookLoanAction,
    $markBookAsReturnedAction
);
$userController = new UserController($registerUserAction, $loginAction, $sessionManager);
$loanController = new LoanController($loanRequestsAction, $listUserLoansAction);

$basePath = '/BookLoanSystem/public';
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

if (str_starts_with($requestUri, $basePath)) {
    $requestUri = substr($requestUri, strlen($basePath));
}

$routes = [
    '/^\/$/' => [
        "GET" => function () {
            $body = '';
            include __DIR__ . '/../src/View/home.php';
            return new Response($body);
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
    '/^\/logout$/' => [
        "GET" => function () {
            session_destroy();
            header('Location: /');
            },
    ],
    '/^\/books$/' => [
        "GET" => [$bookController, 'index'],
        "POST" => [$bookController, 'add'],
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
    '/^\/books\/search$/' => [
        "POST" => [$bookController, 'search'],
    ],
    '/^\/books\/available$/' => [
        "GET" => [$bookController, 'listAvailableBooks'],
    ],
    '/^\/loans$/' => [
        "GET" => [$loanController, 'index'],
    ],
    '/^\/user\/loans$/' => [
        "POST" => [$loanController, 'listUserLoans'],
    ],
];

$routeFound = false;
foreach ($routes as $route => $routeConfig) {
    if (preg_match($route, $requestUri, $matches)) {
        foreach ($routeConfig as $method => $action) {
            if ($requestMethod === $method) {
                array_shift($matches);
                $response = call_user_func($action, ...$matches);
                $response->send();

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
