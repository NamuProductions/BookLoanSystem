<?php

namespace App\Action\Admin;

use App\Domain\Repository\LoanRepository;

readonly class ListLoanRequestsAction
{
    public function __construct(private LoanRepository $loanRepository)
    {
    }

    public function __invoke(): array
    {
        return $this->loanRepository->findAllLoanRequests();
    }
}
