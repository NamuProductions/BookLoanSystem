<?php
declare(strict_types=1);

namespace App\Controller;

use App\Action\User\RequestBookLoanAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Service\SessionManager;
use InvalidArgumentException;
use JetBrains\PhpStorm\NoReturn;

class BookController
{
    private BookRepository $bookRepository;
    private SessionManager $sessionManager;
    private RequestBookLoanAction $requestBookLoanAction;

    public function __construct(BookRepository $bookRepository, SessionManager $sessionManager, RequestBookLoanAction $requestBookLoanAction)
    {
        $this->bookRepository = $bookRepository;
        $this->sessionManager = $sessionManager;
        $this->requestBookLoanAction = $requestBookLoanAction;
    }

    public function index(): void
    {
        $books = $this->bookRepository->findAll();
        require __DIR__ . '/../View/books/books.php';
    }

    public function show(string $bookId): void
    {
        $book = $this->findBookOrFail($bookId);
        require __DIR__ . '/../View/books/show.php';
    }

    public function borrow(string $bookId): void
    {
        $this->ensureAuthenticated();
        $user = $this->sessionManager->getUser();

        try{
            $this->requestBookLoanAction->__invoke($user->userName(), $bookId);
            $this->redirect();
        } catch (InvalidArgumentException $e){
            $this->sendResponse(400, $e->getMessage());
        }
    }

    public function return(string $bookId): void
    {
        $this->ensureAuthenticated();
        $user = $this->sessionManager->getUser();
        try {
            $this->bookRepository->returnBook($bookId, $user->userId());
            $this->redirect();
        } catch (InvalidArgumentException $e) {
            $this->sendResponse(400, $e->getMessage());
        }
    }

    private function ensureAuthenticated(): void
    {
        if (!$this->sessionManager->isAuthenticated()) {
            $this->sendResponse(403, 'User not logged in');
        }

        if (!$this->sessionManager->getUser()) {
            $this->sendResponse(404, 'User not found');
        }
    }

    private function findBookOrFail(string $bookId): Book
    {
        $book = $this->bookRepository->findById($bookId);
        if (!$book) {
            $this->sendResponse(404, 'Book not found');
        }
        return $book;
    }

    #[NoReturn] private function sendResponse(int $statusCode, string $message): void
    {
        http_response_code($statusCode);
        echo $message;
        exit;
    }

    #[NoReturn] private function redirect(): void
    {
        header('Location: ' . '/books');
        exit;
    }
}
