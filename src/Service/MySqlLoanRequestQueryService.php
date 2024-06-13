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
        $statement = $this->databaseConnection->prepare('SELECT book_id, title, user_name, user_id, borrow_at FROM loan_requests
                                                join user on user.id = loan_requests.userId
                                                join book on book.id = loan_requests.bookId
                                                where status = "pending"'
        );
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_FUNC, function($bookId, $title, $userName, $userId, $borrowedAtDateString){
            return new LoanRequestsDto($bookId, $title, $userName, $userId, new DateTime($borrowedAtDateString));
        });
    }
}