<?php
declare(strict_types=1);

namespace App\Domain\Model;

use DateTime;

class User
{
    private int $userId;
    private string $userName;
    private string $password;
    private string $email;
    private ?string $fullName;
    private ?int $age;
    private DateTime $createdAt;
    private string $role;

    public function __construct(
        string $userName,
        string $password,
        string $email,
        ?string $fullName = null,
        ?int $age = null,
        ?string $role = 'user',
        ?int $userId = null,
        ?DateTime $createdAt = null
    ) {
        $this->userName = $userName;
        $this->password = $password;
        $this->email = $email;
        $this->fullName = $fullName;
        $this->age = $age;
        $this->role = $role ?? 'user';
        $this->userId = $userId ?? 0;
        $this->createdAt = $createdAt ?? new DateTime();
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function userName(): string
    {
        return $this->userName;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function fullName(): ?string
    {
        return $this->fullName;
    }

    public function age(): ?int
    {
        return $this->age;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function role(): string
    {
        return $this->role;
    }
}
