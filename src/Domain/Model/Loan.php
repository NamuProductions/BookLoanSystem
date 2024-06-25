<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\DateRange;
use DateTime;

class Loan
{
    private ?DateTime $realReturnDate = null;

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

    public function markAsReturned(DateTime $realReturnDate): void
    {
        $this->realReturnDate = $realReturnDate;
    }

    public function isReturned(): bool
    {
        return $this->dateRange->realReturnDate() !== null;
    }

    public function realReturnDate(): ?DateTime
    {
        return $this->realReturnDate;
    }
}
