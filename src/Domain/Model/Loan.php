<?php

namespace App\Domain\Model;

class Loan
{
    public function __construct(
        private string $userName,
        private string $bookTitle,
        private string $startDate,
        private string $endDate
    ) {}

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getBookTitle(): string
    {
        return $this->bookTitle;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }
}
