<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Year;
use DateTime;
use InvalidArgumentException;

class Book
{
    private array $loans = [];

    public function __construct(
        private readonly string $title,
        private readonly string $author,
        private readonly Year   $year,
        private readonly string $bookId,
        private bool            $isAvailable = true
    )
    {
    }

    public function title(): string {return $this->title;}

    public function author(): string {return $this->author;}

    public function year(): Year {return $this->year;}

    public function bookId(): string {return $this->bookId;}

    public function isAvailable(): bool {return $this->isAvailable;}

    public function notReturnedLoans(): array
    {
        return array_filter($this->loans, fn($loan) => !$loan->isReturned());
    }

    public function returnedLoans(): array
    {
        return array_filter($this->loans, fn($loan) => $loan->isReturned());
    }

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
        $borrowDate = $borrowDate ?? new DateTime();
        $dueDate = (clone $borrowDate)->modify('+14 days');
        $loan = new Loan($this->bookId, $userId, $borrowDate, $dueDate);
        $this->loans[] = $loan;
        $this->markAsUnavailable();
        return $loan;
    }

    public function returnBook(): void
    {
        if ($this->isAvailable) {
            throw new InvalidArgumentException('Book is already available.');
        }
        $loan = end($this->loans);
        if ($loan instanceof Loan) {
            $loan->markAsReturned(new DateTime());
        }
        $this->markAsAvailable();
    }

    public function findByUser(string $userName): array
    {
        return array_filter($this->loans, fn($loan) => $loan->getUserId() === $userName);
    }

    public function findActiveLoanByUserAndBook(string $userId, string $bookId): ?Loan
    {
        foreach ($this->loans as $loan) {
            if ($loan->getUserId() === $userId && $loan->getBookId() === $bookId && !$loan->isReturned()) {
                return $loan;
            }
        }
        return null;
    }
}
