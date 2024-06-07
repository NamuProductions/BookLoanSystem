<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Repository\BookRepository;

readonly class LoanRequestQueryService implements LoanRequestQueryServiceInterface
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function allLoanRequests(): array
    {
        $allBooks = $this->bookRepository->findAll();
        $loanRequests = [];
        foreach ($allBooks as $book) {
            $loanRequests = array_merge($loanRequests, $book->notReturnedLoans());
        }
        return $loanRequests;
    }
}