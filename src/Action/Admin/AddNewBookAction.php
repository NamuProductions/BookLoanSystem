<?php

namespace App\Action\Admin;


use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use PHPUnit\Event\InvalidArgumentException;

readonly class AddNewBookAction
{

    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(string $title, string $author, string $year, string $idNumber): void
    {
        if(empty($title) || empty($author) || empty($year) || empty($idNumber)) {
            throw new InvalidArgumentException('Title, author, year and idNumber are required');
        }

        $book = new Book($title, $author, $year, $idNumber);
        $this->bookRepository->save($book);
    }
}