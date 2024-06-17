<?php
declare(strict_types=1);

namespace App\Service;

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
        $statement = $this->databaseConnection->prepare("SELECT lr.book_id, b.title, u.user_name, u.id AS user_id, lr.borrow_at, lr.return_at
                                                        FROM loan_requests lr
                                                          JOIN users u ON lr.user_id = u.id
                                                          JOIN books b ON lr.book_id = b.id
                                                          WHERE lr.status = 'pending'");
        $statement->execute();
        $loanRequests = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $borrowedAt = new DateTime($row['borrow_at']);
            $returnAt = $row['return_at'] ? new DateTime($row['return_at']) : null;

            $loanRequests[] = new LoanRequestDto(
                (string)$row['book_id'],
                (string)$row['title'],
                (string)$row['user_name'],
                (string)$row['user_id'],
                $borrowedAt,
                $returnAt
            );
        }

        return $loanRequests;
    }
}