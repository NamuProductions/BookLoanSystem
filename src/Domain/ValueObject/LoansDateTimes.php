<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

use DateTime;

class LoansDateTimes
{
    private DateTime $borrowDate;
    private DateTime $lastDayOfLoan;
    private ?DateTime $realReturnDate;
    public const LOAN_DAYS = 14;

    public function __construct(DateTime $borrowDate, ?DateTime $realReturnDate = null)
    {
        $this->borrowDate = $borrowDate;
        $this->lastDayOfLoan = (clone $borrowDate)->modify('+' . self::LOAN_DAYS . ' days');
        $this->realReturnDate = $realReturnDate;
    }

    public function borrowDate(): DateTime
    {
        return $this->borrowDate;
    }

    public function lastDayOfLoan(): DateTime
    {
        return $this->lastDayOfLoan;
    }

    public function realReturnDate(): ?DateTime
    {
        return $this->realReturnDate;
    }

    public function duration(): ?int
    {
        if ($this->realReturnDate === null) {
            return null;
        }
        $interval = $this->borrowDate->diff($this->realReturnDate);
        return $interval->days;
    }

    public function includes(DateTime $date): bool // TODO: usarlo si es false para penalizar al usuario
    {
        return $date >= $this->borrowDate && $date <= $this->lastDayOfLoan;
    }
}
