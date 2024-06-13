<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\DatabaseService;
use App\Service\LoanRequestQueryService;

class LoanRequestController
{
    public function index()
    {
        $databaseService = new DatabaseService();
        $pdo = $databaseService->getDatabaseConnection();

        $loanRequestService = new LoanRequestQueryService($pdo);

       $loanRequests = $loanRequestService->getAllLoanRequests();

       foreach ($loanRequests as $loanRequest) {
            echo "ID: {$loanRequest['id']}, Book Title: {$loanRequest['book_title']}, User Name: {$loanRequest['user_name']}, Borrowed At: {$loanRequest['borrow_at']->format('Y-m-d')}";
            echo "<br>";
        }
    }
}
