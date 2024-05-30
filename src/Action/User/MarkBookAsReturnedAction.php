<?php

namespace App\Action\User;

use App\Domain\Repository\LoanRepository;
use App\Domain\Repository\BookRepository;
use InvalidArgumentException;

readonly class MarkBookAsReturnedAction
{
    public function __construct(
        private LoanRepository $loanRepository,
        private BookRepository $bookRepository
    ) {}

    public function __invoke(string $userId, string $bookId): void
    {
        $loan = $this->loanRepository->findActiveLoan($userId, $bookId);

        if (!$loan) {
            throw new InvalidArgumentException('No active loan found for this book and user.');
        }

        $loan->markAsReturned();
        $this->loanRepository->save($loan);

        $book = $this->bookRepository->findById($bookId);
        $book->markAsAvailable();
        $this->bookRepository->save($book);
    }
}
