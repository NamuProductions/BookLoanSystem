<?php

namespace App\Action\User;

use App\Domain\Repository\BookRepository;
use App\Domain\Repository\LoanRepository;
use App\Domain\Repository\UserRepository;

readonly class RequestBookLoanAction
{
    public function __construct(
        private BookRepository $bookRepository,
        private LoanRepository $loanRepository,
        private UserRepository $userRepository
    ) {}

    public function __invoke(string $userId, string $bookId): void
    {
    }
}