<?php

namespace App\Domain\Model;

readonly class Loan
{
    public function __construct(
        private string $user,
        private string $book,
        private string $loanDate,
        private string $returnDate
    ) {}

    public function getUser(): string
    {
        return $this->user;
    }

    public function getBook(): string
    {
        return $this->book;
    }

    public function getLoanDate(): string
    {
        return $this->loanDate;
    }

    public function getReturnDate(): string
    {
        return $this->returnDate;
    }
}
