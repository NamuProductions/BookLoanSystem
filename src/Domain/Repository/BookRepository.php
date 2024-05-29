<?php

namespace App\Domain\Repository;

use App\Domain\Model\Book;

interface BookRepository
{
    public function save(Book $book): void;

    public function findAvailableBooks(): array;

    public function search(string $query): array;
}