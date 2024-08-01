<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;

class UserName
{
    private string $userName;

    public function __construct(string $userName)
    {
        if (empty($userName)) {
            throw new InvalidArgumentException('Username cannot be empty.');
        }

        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $userName)) {
            throw new InvalidArgumentException('Invalid username format.');
        }

        $this->userName = $userName;
    }

    public function value(): string
    {
        return $this->userName;
    }
}
