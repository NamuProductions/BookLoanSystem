<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;

class Year
{
    private int $year;
    private const MIN_YEAR_BC = -10000;
    private const MAX_YEAR_AD = 10000;
    private const YEAR_ZERO = 0;

    public function __construct(int $year)
    {
        $this->validateYear($year);
        $this->year = $year;
    }

    private function validateYear(int $year): void
    {
        $currentYear = (int)date("Y");
        if ($year == self::YEAR_ZERO || $year < self::MIN_YEAR_BC || $year > ($currentYear + self::MAX_YEAR_AD)) {
            throw new InvalidArgumentException('Invalid Year');
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
