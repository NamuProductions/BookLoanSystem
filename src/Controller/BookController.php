<?php
declare(strict_types=1);

namespace App\Controller;

use App\Action\User\RequestBookLoanAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Service\SessionManager;
use InvalidArgumentException;

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

    public function index(): Response
    {
        ob_start();
        $books = $this->bookRepository->findAll();
        require __DIR__ . '/../View/books/books.php';
        $body = ob_get_clean();
        return new Response($body);
    }

    public function show(string $bookId): Response
    {
        ob_start();
        $book = $this->findBookOrFail($bookId);
        require __DIR__ . '/../View/books/show.php';
        $body = ob_get_clean();
        return new Response($body);
    }

    public function borrow(string $bookId): Response
    {
        try {
            $this->ensureAuthenticated();
            $user = $this->sessionManager->getUser();
            $this->requestBookLoanAction->__invoke($user->userName(), $bookId);
            return new RedirectResponse('/books');
        } catch (InvalidArgumentException $e) {
            return new Response($e->getMessage(), 400);
        } catch (NotAuthenticatedException) {
            return new RedirectResponse('/login');
        }
    }

    public function return(string $bookId): Response
    {
        try {
            $this->ensureAuthenticated();
            $user = $this->sessionManager->getUser();
            $this->bookRepository->returnBook($bookId, $user->userId());
            return new RedirectResponse('/books');
        } catch (InvalidArgumentException $e) {
            return new Response($e->getMessage(), 400);
        } catch (NotAuthenticatedException) {
            return new RedirectResponse('/login');
        }
    }

    private function ensureAuthenticated(): void
    {
        if (!$this->sessionManager->isAuthenticated() || !$this->sessionManager->getUser()) {
            throw new NotAuthenticatedException();
        }
    }

    private function findBookOrFail(string $bookId): Book
    {
        $book = $this->bookRepository->findById($bookId);
        if (!$book) {
            throw new InvalidArgumentException('Book not found', 404);
        }
        return $book;
    }
}
