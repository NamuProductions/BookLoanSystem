<?php
declare(strict_types=1);

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
        if (empty($title)) {
            throw new InvalidArgumentException('Title is required');
        }
        if (empty($author)) {
            throw new InvalidArgumentException('Author is required');
        }
        if (empty($year)) {
            throw new InvalidArgumentException('Year is required');
        }
        if (empty($idNumber)) {
            throw new InvalidArgumentException('IdNumber is required');
        }
        $currentYear = (int)date('Y');
        $yearInt = (int)$year;
        if ($yearInt < 0 || $yearInt > $currentYear || (string)$yearInt !== $year) {
            throw new InvalidArgumentException('Year must be an integer between 0 and the current year.');
        }
        $book = new Book($title, $author, $year, $idNumber);
        $this->bookRepository->save($book);
    }
}