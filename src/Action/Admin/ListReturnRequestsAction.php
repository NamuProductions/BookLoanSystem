<?php

namespace App\Action\Admin;

use App\Domain\Repository\LoanRepository;
use Exception;

readonly class ListReturnRequestsAction
{
    public function __construct(private LoanRepository $loanRepository)
    {
    }

    public function __invoke(): array
    {
        try {
            return $this->loanRepository->findAllReturnRequests();
        } catch (Exception) {
            throw new Exception('Error retrieving return requests');
        }
    }
}
