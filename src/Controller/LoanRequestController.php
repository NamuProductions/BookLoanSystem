<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\DatabaseService;
use App\Service\MySqlLoanRequestQueryService;
use App\Service\LoanRequestDto;

class LoanRequestController
{
    public function index(): void
    {
        $databaseService = new DatabaseService();
        $pdo = $databaseService->getDatabaseConnection();

        $loanRequestService = new MySqlLoanRequestQueryService($pdo);

        $loanRequests = $loanRequestService->allLoanRequests();

        foreach ($loanRequests as $loanRequest) {
            echo "ID: {$loanRequest->getBookId()}, Book Title: {$loanRequest->getTitle()}, User Name: {$loanRequest->getUserName()}, Borrowed At: {$loanRequest->getBorrowedAt()->format('Y-m-d')}";
            echo "<br>";
        }
    }
}
