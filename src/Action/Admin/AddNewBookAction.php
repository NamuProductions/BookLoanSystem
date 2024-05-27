<?php

namespace App\Action\Admin;


use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;

readonly class AddNewBookAction
{

    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(string $title, string $author, string $year, string $idNumber): void
    {
        $book = new Book($title, $author, $year, $idNumber);
        $this->bookRepository->save($book);
    }
}