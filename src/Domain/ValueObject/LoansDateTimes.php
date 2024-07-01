<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

use DateTime;

class LoansDateTimes
{
    private DateTime $loanBorrowedAt;
    private DateTime $LoanMaximumReturnDate;
    private ?DateTime $LoanReturnedAt;
    public const LOAN_DAYS = 14;

    public function __construct(DateTime $borrowDate, ?DateTime $realReturnDate = null)
    {
        $this->loanBorrowedAt = $borrowDate;
        $this->LoanMaximumReturnDate = (clone $borrowDate)->modify('+' . self::LOAN_DAYS . ' days');
        $this->LoanReturnedAt = $realReturnDate;
    }

    public function loanBorrowedAt(): DateTime
    {
        return $this->loanBorrowedAt;
    }

    public function LoanMaximumReturnDate(): DateTime
    {
        return $this->LoanMaximumReturnDate;
    }

    public function LoanReturnedAt(): ?DateTime
    {
        return $this->LoanReturnedAt;
    }

    public function duration(): ?int
    {
        if ($this->LoanReturnedAt === null) {
            return null;
        }
        $interval = $this->loanBorrowedAt->diff($this->LoanReturnedAt);
        return $interval->days;
    }

    public function includes(DateTime $date): bool // TODO: usarlo si es false para penalizar al usuario
    {
        return $date >= $this->loanBorrowedAt && $date <= $this->LoanMaximumReturnDate;
    }
}
