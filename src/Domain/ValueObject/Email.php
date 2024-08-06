<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;

final class Email
{
    private string $value;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^.+@[^-][A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $email)) {
            throw new InvalidArgumentException('Invalid email address.');
        }

        $this->value = $email;
    }

    public function value(): string
    {
        return $this->value;
    }
}
