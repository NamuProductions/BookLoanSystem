<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Book;

interface BookRepository
{
    public function ofId(string $bookId): ?Book;
    public function save(Book $book): void;
}
