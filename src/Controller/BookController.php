<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\Repository\BookRepository;
use App\Domain\Repository\UserRepository;
use InvalidArgumentException;

class BookController
{
    private BookRepository $bookRepository;
    private UserRepository $userRepository;

    public function __construct(BookRepository $bookRepository, UserRepository $userRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->userRepository = $userRepository;
    }

    public function index(): void
    {
        $books = $this->bookRepository->findAll();
        require __DIR__ . '/../View/books/books.php';
    }

    public function show(string $bookId): void
    {
        $book = $this->bookRepository->findById($bookId);
        if (!$book) {
            http_response_code(404);
            echo "Book not found";
            return;
        }
        require __DIR__ . '/../View/books/show.php';
    }

    public function borrow(string $bookId): void
    {
        $userId = $_SESSION['userId'];
        $book = $this->bookRepository->findById($bookId);

        if ($book && $book->isAvailable()) {
            $this->bookRepository->borrowBook($bookId, $userId);
            header('Location: /books');
            exit;
        } else {
            echo "Book is not available.";
        }
    }

    public function return(string $bookId): void
    {
        if (!isset($_SESSION['userId'])) {
            http_response_code(400);
            echo "User not logged in";
            return;
        }

        $userId = $_SESSION['userId'];
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            http_response_code(404);
            echo "User not found";
            return;
        }

        $book = $this->bookRepository->findById($bookId);
        if ($book === null) {
            http_response_code(404);
            echo "Book not found";
            return;
        }
        try {
            $this->bookRepository->returnBook($bookId, $userId);
            header('Location: /books');
            echo 'Book returned successfully';
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }
}
