<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;


class Year
{
    private int $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

        public function getValue(): int
    {
        return $this->year;
    }

    public function __toString(): string
    {
        if ($this->year < 0) {
            return abs($this->year) . "B.C.";
        }
        return (string)$this->year;
    }
}
