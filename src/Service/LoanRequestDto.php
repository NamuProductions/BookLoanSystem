<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\ValueObject\DateRange;

readonly class LoanRequestDto
{
    public string $bookId;
    public string $title;
    public string $userName;
    public string $userId;
    public DateRange $dateRange;

    public function __construct(
        string   $bookId,
        string   $title,
        string   $userName,
        string   $userId,
        DateRange $dateRange
    )
    {
        $this->bookId = $bookId;
        $this->title = $title;
        $this->userName = $userName;
        $this->userId = $userId;
        $this->dateRange = $dateRange;
    }
}
