<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Domain\Repository\BookRepository;
use InvalidArgumentException;

readonly class MarkBookAsReturnedAction
{
    public function __construct(
        private BookRepository $bookRepository
    ) {}

    public function __invoke(string $userId, string $bookId): void
    {
        $book = $this->bookRepository->findById($bookId);

        if ($book === null) {
            throw new InvalidArgumentException('Book not found.');
        }

        $book->returnBook($userId);

        $this->bookRepository->save($book);
    }
}
