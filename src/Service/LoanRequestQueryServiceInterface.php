<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Model\Loan;

interface LoanRequestQueryServiceInterface
{
    public function getAllLoanRequests(): array;
}
