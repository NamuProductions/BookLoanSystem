<?php
declare(strict_types=1);

namespace App\Service;


interface LoanRequestQueryServiceInterface
{
    public function allLoanRequests(): array;
}
