<?php

namespace App\Action\User;

use App\Domain\Repository\BookRepository;

readonly class ListAvailableBooksAction
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(): array
    {
        return $this->bookRepository->findAvailableBooks();
    }
}
