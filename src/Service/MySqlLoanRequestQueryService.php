<?php
declare(strict_types=1);

namespace App\Service;

use App\Action\Admin\LoanRequestsAction;
use DateTime;
use PDO;

readonly class MySqlLoanRequestQueryService implements LoanRequestQueryServiceInterface
{
    public function __construct(private PDO $databaseConnection)
    {
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