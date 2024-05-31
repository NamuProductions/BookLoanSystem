<?php
declare(strict_types=1);

namespace App\Domain\Model;

class Book
{
    public function __construct(
        private readonly string $title,
        private readonly string $author,
        private readonly string $year,
        private readonly string $idNumber,
        private bool $isAvailable = true
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getYear(): string
    {
        return $this->year;
    }

    public function getIdNumber(): string
    {
        return $this->idNumber;
    }
    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function markAsUnavailable(): void
    {
        $this->isAvailable = false;
    }

    public function markAsAvailable(): void
    {
        $this->isAvailable = true;
    }
}