<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\LoansDateTimes;
use App\Util\UUID;
use DateTime;

class Loan
{
    private string $loanId;
    private string $status;
    private ?DateTime $loanReturnedAt = null;

    public function __construct(
        private readonly string $bookId,
        private readonly string $userId,
        private readonly LoansDateTimes $loansDateTimes,
        ?string $loanId = null,
        string $status = 'borrowed'
    ) {
        $this->loanId = $loanId ?? UUID::generate();
        $this->status = $status;
    }

    public function loanId(): string
    {
        return $this->loanId;
    }

    public function bookId(): string
    {
        return $this->bookId;
    }

    public function userId(): string
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
