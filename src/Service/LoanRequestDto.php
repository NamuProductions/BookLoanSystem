<?php
declare(strict_types=1);

namespace App\Service;

use DateTime;

readonly class LoanRequestDto
{
    public string $bookId;
    public string $title;
    public string $userName;
    public string $userId;
    public DateTime $borrowedAt;

    public function __construct(
        string   $bookId,
        string   $title,
        string   $userName,
        string   $userId,
        DateTime $borrowedAt
    )
    {
        $this->bookId = $bookId;
        $this->title = $title;
        $this->userName = $userName;
        $this->userId = $userId;
        $this->borrowedAt = $borrowedAt;
    }
}
