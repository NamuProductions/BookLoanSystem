<?php

namespace App\Action\User;

use App\Domain\Repository\BookRepository;

class ListUserLoansAction
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function execute(string $userId): array
    {
        return $this->bookRepository->findAllLoansByUser($userId);
    }

}

