<?php
declare(strict_types=1);

namespace App\Action\Admin;

use App\Service\LoanRequestQueryServiceInterface;
use Exception;

readonly class LoanRequestsAction
{
    private LoanRequestQueryServiceInterface $loanRequestQueryService;
    public function __construct(LoanRequestQueryServiceInterface $loanRequestQueryService)
    {
        $this->loanRequestQueryService = $loanRequestQueryService;
    }

    public function __invoke(): array
    {
        try {
            return $this->loanRequestQueryService->allLoanRequests();
        } catch (Exception) {
            throw new Exception('Error retrieving loan requests');
        }
    }
}
