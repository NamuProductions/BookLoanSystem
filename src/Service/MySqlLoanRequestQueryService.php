<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\ValueObject\DateRange;
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
        $statement = $this->databaseConnection->prepare("SELECT lr.book_id, b.title, u.username, u.id AS user_id, lr.borrowed_at, lr.return_date
                                                        FROM loan_requests lr
                                                          JOIN users u ON lr.user_id = u.id
                                                          JOIN books b ON lr.book_id = b.id
                                                          WHERE lr.status = 'pending'");
        $statement->execute();
        $loanRequests = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $borrowedAt = new DateTime($row['borrowed_at']);
            $returnAt = $row['return_date'] ? new DateTime($row['return_date']) : null;
            $dateRange = new DateRange($borrowedAt, $returnAt);

            $loanRequests[] = new LoanRequestDto(
                (string)$row['book_id'],
                (string)$row['title'],
                (string)$row['username'],
                (string)$row['user_id'],
                $dateRange
            );
        }

        return $loanRequests;
    }
}