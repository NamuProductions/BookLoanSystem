<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;

class Password
{
    private string $value;

    public function __construct(string $value)
    {
        if (strlen($value) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters long.');
        }

        if (!preg_match('/[A-Z]/', $value)) {
            throw new InvalidArgumentException('Password must contain at least one uppercase letter.');
        }

        if (!preg_match('/[a-z]/', $value)) {
            throw new InvalidArgumentException('Password must contain at least one lowercase letter.');
        }

        if (!preg_match('/[0-9]/', $value)) {
            throw new InvalidArgumentException('Password must contain at least one digit.');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
