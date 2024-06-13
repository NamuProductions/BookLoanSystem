<?php
declare(strict_types=1);

namespace App\Service;

use DateTime;
use PDO;

readonly class MySqlLoanRequestQueryService implements LoanRequestQueryServiceInterface
{
    private PDO $databaseConnection;

    public function __construct()
    {
        $this->databaseConnection = new PDO('mysql:host=localhost;port=3307;dbname=library', 'root', 'root');
    }

    public function allLoanRequests(): array
    {
        $statement = $this->databaseConnection->prepare("SELECT lr.book_id, b.title, u.user_name, u.id AS user_id, lr.borrow_at 
                                                          FROM loan_requests lr
                                                          JOIN users u ON lr.user_id = u.id
                                                          JOIN books b ON lr.book_id = b.id
                                                          WHERE lr.status = 'pending'"
        );
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_FUNC, function($bookId, $title, $userName, $userId, $borrowedAtDateString){
            return new LoanRequestsDto($bookId, $title, $userName, $userId, new DateTime($borrowedAtDateString));
        });
    }
}