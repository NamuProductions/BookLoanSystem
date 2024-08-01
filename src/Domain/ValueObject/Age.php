<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;

class Age
{
    private int $age;
    public function __construct(int $age)
    {
        if ($age < 0 || $age > 150) {
            throw new InvalidArgumentException("Invalid age value.");
        }
        $this->age = $age;
    }

    public function value(): int
    {
        return $this->age;
    }
}
