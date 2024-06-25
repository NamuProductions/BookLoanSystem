<?php

namespace App\Domain\ValueObject;

use DateTime;

class DateRange
{
    private DateTime $start;
    private ?DateTime $realEnd;
    private const LOAN_DAYS = 14;

    public function __construct(DateTime $start, ?DateTime $realEnd = null)
    {
        $this->start = $start;
        $this->realEnd = $realEnd;
    }

    public function start(): DateTime
    {
        return $this->start;
    }

    public function end(): ?DateTime
    {
        return (clone $this->start)->modify('+' . self::LOAN_DAYS . ' days');
    }

    public function realEnd(): ?DateTime
    {
        return $this->realEnd;
    }

    public function duration(): ?int
    {
        if ($this->realEnd === null) {
            return null;
        }
        $interval = $this->start->diff($this->realEnd);
        return $interval->days;
    }

    public function includes(DateTime $date): bool
    {
        return $date >= $this->start && $date <= $this->end();
    }

}
