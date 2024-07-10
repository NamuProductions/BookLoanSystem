<?php
declare(strict_types=1);

namespace App\Action\Admin;

use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use InvalidArgumentException;
use DateTime;

readonly class AddNewBookAction
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(
        string    $title,
        string    $author,
        string    $language,
        Year      $year,
        ?int      $pages = null,
        ?string   $genre = null,
        ?string   $bookId = null,
        ?DateTime $createdAt = null,
        bool      $isAvailable = true
    ): void
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

        $book = new Book(
            title: $title,
            year: $year,
            author: $author,
            pages: $pages,
            genre: $genre,
            language: $language,
            isAvailable: $isAvailable,
            bookId: $bookId,
            createdAt: $createdAt
        );

        $this->bookRepository->save($book);
    }
}
