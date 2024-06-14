<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\DatabaseService;
use App\Service\MySqlLoanRequestQueryService;

class LoanRequestController
{
    public function index(): void
    {
        $databaseService = new DatabaseService();
        $pdo = $databaseService->getDatabaseConnection();

        $loanRequestService = new MySqlLoanRequestQueryService($pdo);

        $loanRequests = $loanRequestService->allLoanRequests();

        foreach ($loanRequests as $loanRequest) {
            echo "ID: {$loanRequest['id']}, Book Title: {$loanRequest['title']}, User Name: {$loanRequest['user_name']}, Borrowed At: {$loanRequest['borrow_at']->format('Y-m-d')}";
            echo "<br>";
        }
    }
}
