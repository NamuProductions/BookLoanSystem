<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

use DateTime;

class DateRange
{
    private DateTime $start;
    private ?DateTime $realReturnDate;
    public const LOAN_DAYS = 14;

    public function __construct(DateTime $start, ?DateTime $realReturnDate = null)
    {
        $this->start = $start;
        $this->realReturnDate = $realReturnDate;
    }

    public function start(): DateTime
    {
        return $this->start;
    }

    public function lastDayOfLoan(): DateTime
    {
        return (clone $this->start)->modify('+' . self::LOAN_DAYS . ' days');
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
        $interval = $this->start->diff($this->realReturnDate);
        return $interval->days;
    }

    public function includes(DateTime $date): bool
    {
        return $date >= $this->start && $date <= $this->lastDayOfLoan();
    }
}
