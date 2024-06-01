<?php
declare(strict_types=1);

namespace App\Domain\Model;

use DateTime;

class Loan
{
    private ?DateTime $returnDate = null;

    public function __construct(
        private readonly string $bookId,
        private readonly string $userId,
        private readonly DateTime $borrowDate,
        private readonly DateTime $dueDate
    ) {}

    public function getBookId(): string
    {
        return $this->bookId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getBorrowDate(): DateTime
    {
        return $this->borrowDate;
    }

    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }

    public function getReturnDate(): ?DateTime
    {
        return $this->returnDate;
    }

    public function markAsReturned(DateTime $returnDate): void
    {
        $this->returnDate = $returnDate;
    }

    public function isReturned(): bool
    {
        return $this->returnDate !== null;
    }
}
