<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Repository\BookRepository;

class LoanRequestQueryService implements LoanRequestQueryServiceInterface
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function allLoanRequests(): array
    {
        return $this->bookRepository->findAllLoanRequests();
    }
}
