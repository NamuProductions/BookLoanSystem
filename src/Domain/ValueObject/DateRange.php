<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

use DateTime;

class DateRange
{
    private DateTime $start;
    private DateTime $lastDayOfLoan;
    private ?DateTime $realReturnDate;
    public const LOAN_DAYS = 14;

    public function __construct(DateTime $start, ?DateTime $realReturnDate = null)
    {
        $this->start = $start;
        $this->lastDayOfLoan = (clone $start)->modify('+' . self::LOAN_DAYS . ' days');
        $this->realReturnDate = $realReturnDate;
    }

    public function start(): DateTime
    {
        return $this->start;
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
        $interval = $this->start->diff($this->realReturnDate);
        return $interval->days;
    }

    public function includes(DateTime $date): bool // TODO: usarlo si es false para penalizar al usuario
    {
        return $date >= $this->start && $date <= $this->lastDayOfLoan;
    }
}
