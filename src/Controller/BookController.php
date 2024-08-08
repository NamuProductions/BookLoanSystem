<?php

declare(strict_types=1);

namespace App\Controller;

use App\Action\Admin\AddNewBookAction;
use App\Action\User\ListAvailableBooksAction;
use App\Action\User\SearchBooksAction;
use App\Action\User\MarkBookAsReturnedAction;
use App\Action\User\RequestBookLoanAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use App\Service\SessionManager;
use Exception;
use InvalidArgumentException;

class BookController
{
    private BookRepository $bookRepository;
    private SessionManager $sessionManager;
    private AddNewBookAction $addNewBookAction;
    private ListAvailableBooksAction $listAvailableBooksAction;
    private SearchBooksAction $searchBooksAction;
    private RequestBookLoanAction $requestBookLoanAction;
    private MarkBookAsReturnedAction $markBookAsReturnedAction;

    public function __construct(
        BookRepository $bookRepository,
        SessionManager $sessionManager,
        AddNewBookAction $addNewBookAction,
        ListAvailableBooksAction $listAvailableBooksAction,
        SearchBooksAction $searchBooksAction,
        RequestBookLoanAction $requestBookLoanAction,
        MarkBookAsReturnedAction $markBookAsReturnedAction
    ) {
        $this->bookRepository = $bookRepository;
        $this->sessionManager = $sessionManager;
        $this->addNewBookAction = $addNewBookAction;
        $this->listAvailableBooksAction = $listAvailableBooksAction;
        $this->searchBooksAction = $searchBooksAction;
        $this->requestBookLoanAction = $requestBookLoanAction;
        $this->markBookAsReturnedAction = $markBookAsReturnedAction;
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

    public function add(): Response
    {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $language = $_POST['language'];
        $year = new Year((int) $_POST['year']);
        $pages = $_POST['pages'] ? (int) $_POST['pages'] : null;
        $genre = $_POST['genre'];

        try {
            $this->addNewBookAction->__invoke($title, $author, $language, $year, $pages, $genre);
            return new RedirectResponse('/books');
        } catch (InvalidArgumentException $e) {
            return new Response($e->getMessage(), 400);
        } catch (Exception) {
            return new Response('An error occurred', 500);
        }
    }

    public function listAvailableBooks(): Response
    {
        try {
            $books = ($this->listAvailableBooksAction)();
            ob_start();
            require __DIR__ . '/../View/books/available.php';
            $body = ob_get_clean();
            return new Response($body);
        } catch (Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    public function search(): Response
    {
        $query = $_POST['query'];

        try {
            $books = ($this->searchBooksAction)($query);
            ob_start();
            require __DIR__ . '/../View/books/search_results.php';
            $body = ob_get_clean();
            return new Response($body);
        } catch (Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    public function borrow(string $bookId): Response
    {
        try {
            $this->ensureAuthenticated();
            $user = $this->sessionManager->user();
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
            $user = $this->sessionManager->user();
            $this->markBookAsReturnedAction->__invoke($user->userId(), $bookId);
            return new RedirectResponse('/books');
        } catch (InvalidArgumentException $e) {
            return new Response($e->getMessage(), 400);
        } catch (NotAuthenticatedException) {
            return new RedirectResponse('/login');
        }
    }

    private function ensureAuthenticated(): void
    {
        if (!$this->sessionManager->isAuthenticated() || !$this->sessionManager->user()) {
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
