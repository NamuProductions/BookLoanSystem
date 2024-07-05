<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\LoansDateTimes;
use DateTime;

class Loan
{
    private int $loanId;
    private string $status;
    private ?DateTime $loanReturnedAt = null;

    public function __construct(
        private readonly int $bookId,
        private readonly int $userId,
        private readonly LoansDateTimes $loansDateTimes,
        int $loanId = 0,
        string $status = 'borrowed'
    ) {
        $this->loanId = $loanId;
        $this->status = $status;
    }

    public function loanId(): int
    {
        return $this->loanId;
    }

    public function bookId(): int
    {
        return $this->bookId;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function loansDateTimes(): LoansDateTimes
    {
        return $this->loansDateTimes;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function markAsReturned(DateTime $loanReturnedAt): void
    {
        $this->loanReturnedAt = $loanReturnedAt;
        $this->status = 'returned';
    }

    public function isReturned(): bool
    {
        return $this->loanReturnedAt !== null;
    }

    public function loanReturnedAt(): ?DateTime
    {
        return $this->loanReturnedAt;
    }
}
