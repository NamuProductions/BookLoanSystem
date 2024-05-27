<?php

namespace App\Action\Admin;

use App\Domain\Repository\LoanRepository;
use Exception;

readonly class ListLoanRequestsAction
{
    public function __construct(private LoanRepository $loanRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(): array
    {
        try {
            return $this->loanRepository->findAllLoanRequests();
        } catch (Exception $e) {
            throw new Exception('Error retrieving loan requests', 0, $e);
        }
    }
}
