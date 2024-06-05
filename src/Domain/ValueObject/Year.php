<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;

class Year
{
    private int $year;

    public function __construct(int $year)
    {
        $this->validateYear($year);
        $this->year = $year;
    }

    private function validateYear(int $year): void
    {
        $currentYear = (int)date("Y");
        if if ($year == 0 || $year < -10000 || $year > ($currentYear + 10000)) {
            throw new InvalidArgumentException('Invalid year.');
        }
    }

    public function getValue(): int
    {
        return $this->year;
    }

    public function __toString(): string
    {
        return (string)$this->year;
    }
}
