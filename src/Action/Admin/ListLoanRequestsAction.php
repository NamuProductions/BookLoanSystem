<?php
declare(strict_types=1);

namespace App\Action\Admin;

use App\Service\LoanRequestQueryServiceInterface;
use Exception;

readonly class ListLoanRequestsAction
{
    public function __construct(
        private LoanRequestQueryServiceInterface $loanRequestQueryService)
    {}

    public function __invoke(): array
    {
        try {
            return $this->loanRequestQueryService->allLoanRequests();
        } catch (Exception) {
            throw new Exception('Error retrieving loan requests');
        }
    }
}
