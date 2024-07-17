<?php

namespace App\Controller;

use App\Domain\Repository\BookRepository;
use App\Domain\Repository\UserRepository;
use DateTime;
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

    public function show(int $bookId): void
    {
        $book = $this->bookRepository->findById((string)$bookId);
        if (!$bookId) {
            http_response_code(404);
            echo "Book not found";
            return;
        }
        require __DIR__ . '/../View/books/show.php';
    }

    public function borrow(int $bookId): void
    {
        $userId = $_SESSION['userId'];
        $user = $this->userRepository->findById($userId);
        $book = $this->bookRepository->findById((string)$bookId);

        if ($book->isAvailable()) {
            $book->borrow($user, new DateTime());
            $this->bookRepository->save($book);
            header('Location: /books');
            exit;
        } else {
            echo "Book is not available.";
        }
    }

    public function return(int $bookId): void
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
            $book->returnBook($user->userId());
            $this->bookRepository->save($book);
            header('Location: /books');
            echo 'Book returned successfully';
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }
}
