<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class Age
{
    private int $age;
    public function __construct(int $age)
    {
        $this->age = $age;
    }

    public function value(): int
    {
        return $this->age;
    }
}
