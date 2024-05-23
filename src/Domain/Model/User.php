<?php

namespace App\Domain\Model;

readonly class User
{
    public function __construct(
        private string $userName,
        private string $email,
        private string $password
    ) {}

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}