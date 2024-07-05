<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Action\Admin\LoanRequestsAction;
use App\Domain\ValueObject\Year;
use DateTime;
use InvalidArgumentException;

class Book
{
    private int $bookId;
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
        ?int $bookId = null,
        ?DateTime $createdAt = null
    ) {
        $this->bookId = $bookId ?? 0;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->isAvailable = $isAvailable;
    }

    public function bookId(): int
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

        $loanRequestId = uniqid('loan_', true);

        $this->loanRequests[] = new LoanRequestsAction($loanRequestId, $this->bookId, $user->userId(), $borrowDate);
        $this->isAvailable = false;
    }

    public function returnBook(string $userId): void
    {
        foreach ($this->loanRequests as $loanRequest) {
            if ($loanRequest->userId() === $userId && $loanRequest->returnDate() === null) {
                $loanRequest->setReturnDate(new DateTime());
                $this->isAvailable = true;
                return;
            }
        }
        throw new InvalidArgumentException('No active loan request found for this user.');
    }
}
