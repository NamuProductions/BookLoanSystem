<?php

namespace App\Domain\Repository;

interface LoanRepository
{
    public function findAllLoanRequests(): array;
}