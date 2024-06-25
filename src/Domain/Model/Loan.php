<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\DateRange;
use DateTime;

class Loan
{
    private ?DateTime $returnDate = null;

    public function __construct(
        private readonly string $bookId,
        private readonly string $userId,
        private readonly DateRange $dateRange
    ) {}

    public function getBookId(): string
    {
        return $this->bookId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function dateRange(): DateRange
    {
        return $this->dateRange;
    }

    public function markAsReturned(DateTime $returnDate): void
    {
        $this->returnDate = $returnDate;
    }

    public function isReturned(): bool
    {
        return $this->dateRange->realReturnDate() !== null;
    }

    public function returnDate(): ?DateTime
    {
        return $this->returnDate;
    }
}
