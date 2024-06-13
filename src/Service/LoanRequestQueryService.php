<?php
declare(strict_types=1);

namespace App\Service;

use DateTime;
use PDO;

class LoanRequestQueryService
{
    private PDO $databaseConnection;

    public function __construct(PDO $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    public function getAllLoanRequests(): array
    {
        $statement = $this->databaseConnection->prepare('SELECT lr.id, b.title AS book_title, u.user_name, u.id AS user_id, lr.borrow_at
                                                          FROM loan_requests lr
                                                          JOIN users u ON lr.user_id = u.id
                                                          JOIN books b ON lr.book_id = b.id
                                                          WHERE lr.status = "pending"');

        $statement->execute();

        $loanRequests = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $borrowedAt = new DateTime($row['borrow_at']);
            $loanRequests[] = [
                'id' => $row['id'],
                'book_title' => $row['book_title'],
                'user_name' => $row['user_name'],
                'user_id' => $row['user_id'],
                'borrow_at' => $borrowedAt
            ];
        }

        return $loanRequests;
    }
}
