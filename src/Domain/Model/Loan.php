<?php
declare(strict_types=1);

namespace App\Domain\Model;

class Loan
{
    private bool $isReturned;

    public function __construct(
        private readonly string $user,
        private readonly string $book,
        private readonly string $loanDate,
        private readonly string $returnDate
    ) {
        $this->isReturned = false;
    }

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

    public function isReturned(): bool
    {
        return $this->isReturned;
    }

    public function markAsReturned(): void
    {
        $this->isReturned = true;
    }
}
