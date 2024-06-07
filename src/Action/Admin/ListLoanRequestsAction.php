<?php
declare(strict_types=1);

namespace App\Action\Admin;

use App\Service\LoanRequestQueryService;
use Exception;

readonly class ListLoanRequestsAction
{
    public function __construct(
        private LoanRequestQueryService $loanRequestQueryService)
    {}

    public function __invoke(): array
    {
        try {
            return $this->loanRequestQueryService->getAllLoanRequests();
        } catch (Exception) {
            throw new Exception('Error retrieving loan requests');
        }
    }
}
