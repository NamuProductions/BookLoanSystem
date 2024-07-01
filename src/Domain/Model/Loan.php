<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\LoansDateTimes;
use DateTime;

class Loan
{
    private ?DateTime $loanReturnedAt = null;

    public function __construct(
        private readonly string $bookId,
        private readonly string $userId,
        private readonly LoansDateTimes $loansDateTimes
    ) {}

    public function getBookId(): string
    {
        return $this->bookId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function loansDateTimes(): LoansDateTimes
    {
        return $this->loansDateTimes;
    }

    public function markAsReturned(DateTime $LoanReturnedAt): void
    {
        $this->loanReturnedAt = $LoanReturnedAt;
    }

    public function isReturned(): bool
    {
        return $this->loanReturnedAt() !== null;
    }

    public function loanReturnedAt(): ?DateTime
    {
        return $this->loanReturnedAt;
    }
}
