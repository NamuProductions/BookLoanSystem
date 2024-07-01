<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;

class FullName
{
    private string $firstName;
    private string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        if (empty($firstName)) {
            throw new InvalidArgumentException('First name cannot be empty.');
        }

        if (empty($lastName)) {
            throw new InvalidArgumentException('Last name cannot be empty.');
        }

        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function fullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }
}
