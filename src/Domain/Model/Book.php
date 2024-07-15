<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\LoansDateTimes;
use App\Domain\ValueObject\Year;
use App\Util\UUID;
use DateTime;
use InvalidArgumentException;

class Book
{
    private string $bookId;
    private DateTime $createdAt;
    private bool $isAvailable;
    private array $loanRequests = [];

    public function __construct(
        private readonly string $title,
        private readonly Year $year,
        private readonly string $author,
        private readonly ?int $pages,
        private readonly ?string $genre,
        private readonly string $language,
        bool $isAvailable = true,
        ?string $bookId = null,
        ?DateTime $createdAt = null
    ) {
        $this->bookId = $bookId ?? UUID::generate();
        $this->createdAt = $createdAt ?? new DateTime();
        $this->isAvailable = $isAvailable;
    }

    public function bookId(): string
    {
        return $this->bookId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function year(): Year
    {
        return $this->year;
    }

    public function author(): string
    {
        return $this->author;
    }

    public function pages(): ?int
    {
        return $this->pages;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function language(): string
    {
        return $this->language;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function borrow(User $user, DateTime $borrowDate): void
    {
        if (!$this->isAvailable) {
            throw new InvalidArgumentException('Book is not available for borrowing.');
        }

        $loan = new Loan(
            bookId: $this->bookId,
            userId: $user->userId(),
            loansDateTimes: new LoansDateTimes($borrowDate)
        );

        $this->loanRequests[] = $loan;
        $this->isAvailable = false;
    }

    public function returnBook(string $userId): void
    {
        foreach ($this->loanRequests as $loanRequest) {
            if ($loanRequest->userId() === $userId && !$loanRequest->isReturned()) {
                $loanRequest->markAsReturned(new DateTime());
                $this->isAvailable = true;
                return;
            }
        }
        throw new InvalidArgumentException('No active loan request found for this user.');
    }

    public function findAllLoansByUser(string $userId): array
    {
        return array_filter($this->loanRequests, fn($loanRequest) => $loanRequest->userId() === $userId);
    }
}
