<?php
declare(strict_types=1);

namespace App\Controller;

use App\Action\Admin\LoanRequestsAction;
use Exception;

class LoanRequestController
{
    private LoanRequestsAction $loanRequestsAction;

    public function __construct(LoanRequestsAction $loanRequestsAction)
    {
        $this->loanRequestsAction = $loanRequestsAction;
    }

    public function index(): array
    {
        try {
            return ($this->loanRequestsAction)();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
