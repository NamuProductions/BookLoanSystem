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
    public ?DateTime $returnDate;

    public function __construct(
        string   $bookId,
        string   $title,
        string   $userName,
        string   $userId,
        DateTime $borrowedAt,
        ?DateTime $returnDate = null
    )
    {
        $this->bookId = $bookId;
        $this->title = $title;
        $this->userName = $userName;
        $this->userId = $userId;
        $this->borrowedAt = $borrowedAt;
        $this->returnDate = $returnDate;
    }
}
