<?php
declare(strict_types=1);

namespace App\Service;

use DateTime;

class LoanRequestsDto
{
    private string $bookId;
    private string $title;
    private string $userName;
    private string $userId;
    private DateTime $borrowedAt;

    public function __construct(
        string $bookId,
        string $title,
        string $userName,
        string $userId,
        DateTime $borrowedAt
    ) {
        $this->bookId = $bookId;
        $this->title = $title;
        $this->userName = $userName;
        $this->userId = $userId;
        $this->borrowedAt = $borrowedAt;
    }

    public function getBookId(): string { return $this->bookId; }
    public function getTitle(): string { return $this->title; }
    public function getUserName(): string { return $this->userName; }
    public function getUserId(): string { return $this->userId; }
    public function getBorrowedAt(): DateTime { return $this->borrowedAt; }
}
