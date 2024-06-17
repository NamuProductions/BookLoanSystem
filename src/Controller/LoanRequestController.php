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
            $returnDate = $loanRequest->returnDate ? $loanRequest->returnDate->format('Y-m-d') : 'Not Returned';
            echo "ID: $loanRequest->bookId, Book Title: $loanRequest->title, User Name: $loanRequest->userName, Borrowed At: {$loanRequest->borrowedAt->format('Y-m-d')}, Return Date: $returnDate";
            echo "<br>";
        }
    }
}
