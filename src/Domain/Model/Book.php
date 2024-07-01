<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Collection\LoanCollection;
use App\Domain\ValueObject\Year;
use App\Domain\ValueObject\LoansDateTimes;
use DateTime;
use InvalidArgumentException;

class Book
{
    private LoanCollection $loans;

    public function __construct(
        private readonly string $title,
        private readonly string $author,
        private readonly string $language,
        private readonly Year $year,
        private readonly string $bookId,
        private bool $isAvailable = true
    ) {
        $this->loans = new LoanCollection();
    }

    public function title(): string { return $this->title; }
    public function author(): string { return $this->author; }
    public function language(): string { return $this->language; }
    public function year(): Year { return $this->year; }
    public function bookId(): string { return $this->bookId; }
    public function isAvailable(): bool { return $this->isAvailable; }

    private function markAsUnavailable(): void
    {
        $this->isAvailable = false;
    }

    private function markAsAvailable(): void
    {
        $this->isAvailable = true;
    }

    public function borrow(string $userId, DateTime $borrowDate = null): Loan
    {
        if (!$this->isAvailable) {
            throw new InvalidArgumentException('Book is already borrowed.');
        }
        $loan = new Loan($this->bookId, $userId, new LoansDateTimes($borrowDate ?? new DateTime()));
        $this->loans->addLoan($loan);
        $this->markAsUnavailable();
        return $loan;
    }

    public function returnBook(string $userId): void
    {
        $activeLoan = $this->loans->findActiveLoanByUser($userId);
        if ($activeLoan === null) {
            throw new InvalidArgumentException('No active loan found for this book and user.');
        }
        $activeLoan->markAsReturned(new DateTime());
        $this->markAsAvailable();
    }

    public function findAllLoansByUser(string $userId): array
    {
        return $this->loans->findAllLoansByUser($userId);
    }
}
