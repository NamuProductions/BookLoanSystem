<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\UserName;
use App\Util\UUID;
use DateTime;

class User
{
    private string $userId;
    private UserName $userName;
    private string $password;
    private string $email;
    private ?string $fullName;
    private Age $age;
    private DateTime $createdAt;
    private string $role;

    public function __construct(
        UserName $userName,
        string $password,
        string $email,
        string $fullName,
        Age $age,
        ?string $role = 'user',
        ?string $userId = null,
        ?DateTime $createdAt = null
    ) {
        $this->userName = $userName;
        $this->password = $password;
        $this->email = $email;
        $this->fullName = $fullName;
        $this->age = $age;
        $this->role = $role ?? 'user';
        $this->userId = $userId ?? UUID::generate();
        $this->createdAt = $createdAt ?? new DateTime();
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function userName(): UserName
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

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function age(): Age
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
