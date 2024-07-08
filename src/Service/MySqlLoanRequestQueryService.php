<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\ValueObject\LoansDateTimes;
use DateTime;
use PDO;

class MySqlLoanRequestQueryService implements LoanRequestQueryServiceInterface
{
    private PDO $databaseConnection;

    public function __construct(PDO $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    public function allLoanRequests(): array
    {
        $statement = $this->databaseConnection->prepare("SELECT l.book_id, b.title, u.user_name, u.user_id AS user_id, l.borrowed_at, l.returned_at, l.status
                                                        FROM loans l
                                                          JOIN users u ON l.user_id = u.user_id
                                                          JOIN books b ON l.book_id = b.book_id
                                                          WHERE l.status = 'pending'");
        $statement->execute();
        $loanRequests = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $borrowedAt = new DateTime($row['borrowed_at']);
            $returnedAt = isset($row['returned_at']) ? new DateTime($row['returned_at']) : null;
            $dateRange = new LoansDateTimes($borrowedAt, $returnedAt);

            $loanRequests[] = new LoanRequestDto(
                (string)$row['book_id'],
                (string)$row['title'],
                (string)$row['user_name'],
                (string)$row['user_id'],
                $dateRange
            );
        }

        return $loanRequests;
    }
}
