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
        require __DIR__ . '/../View/books/index.php';
    }

    public function show(int $id): void
    {
        $book = $this->bookRepository->findById($id);
        require __DIR__ . '/../View/books/show.php';
    }

    public function borrow(int $id): void
    {
        session_start();
        $username = $_SESSION['user'];
        $user = $this->userRepository->findByUserName($username);
        $book = $this->bookRepository->findById($id);

        if ($book->isAvailable()) {
            $book->borrow($user, new DateTime());
            $this->bookRepository->save($book);
            header('Location: /books');
            exit;
        } else {
            echo "Book is not available.";
        }
    }

    public function return(int $id): void
    {
        session_start();
        $username = $_SESSION['user'];
        $user = $this->userRepository->findByUserName($username);
        $book = $this->bookRepository->findById($id);

        try {
            $book->returnBook($user);
            $this->bookRepository->save($book);
            header('Location: /books');
            exit;
        } catch (InvalidArgumentException $e) {
            echo $e->getMessage();
        }
    }
}
