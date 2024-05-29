<?php

namespace App\Domain\Repository;

use App\Domain\Model\Loan;

interface LoanRepository
{
    public function findAllLoanRequests(): array;

    public function findByUser(string $userName): array;

    public function save(Loan $loan): void;
}