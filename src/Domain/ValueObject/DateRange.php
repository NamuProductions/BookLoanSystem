<?php

namespace App\Domain\ValueObject;

use DateTime;

class DateRange
{
    private DateTime $start;
    private ?DateTime $end;

    public function __construct(DateTime $start, ?DateTime $end = null) {
        $this->start = $start;
        $this->end = $end;
    }

    public function start(): DateTime {
        return $this->start;
    }

    public function end(): ?DateTime {
        return $this->end;
    }

    public function duration(): ?int {
        if ($this->end === null) {
            return null;
        }
        $interval = $this->start->diff($this->end);
        return $interval->days;
    }
}
