<?php
declare(strict_types=1);

namespace App\Action\Admin;


use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use PHPUnit\Event\InvalidArgumentException;

readonly class AddNewBookAction
{

    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(string $title, string $author, string $language, Year $year, string $idNumber): void
    {
        if (empty($title)) {
            throw new InvalidArgumentException('Title is required');
        }
        if (empty($author)) {
            throw new InvalidArgumentException('Author is required');
        }
        if (empty($language)) {
            throw new InvalidArgumentException('Language is required');
        }
        if (empty($idNumber)) {
            throw new InvalidArgumentException('IdNumber is required');
        }

        $book = new Book($title, $author, $language, $year, $idNumber);
        $this->bookRepository->save($book);
    }
}