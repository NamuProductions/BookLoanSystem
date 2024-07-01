<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Book;

interface BookRepository
{
    public function save(Book $book): void;

    public function findAvailableBooks(): array;

    public function findAll(): array;

    public function search(string $query): array;

    public function findById(string $id): ?Book;

    public function findAllLoansByUser(string $userId): array;
}