<?php

declare(strict_types=1);

namespace App\Controller;

use App\Action\Admin\LoanRequestsAction;
use App\Action\User\ListUserLoansAction;
use Exception;

class LoanController
{
    private LoanRequestsAction $loanRequestsAction;
    private ListUserLoansAction $listUserLoansAction;

    public function __construct(
        LoanRequestsAction $loanRequestsAction,
        ListUserLoansAction $listUserLoansAction
    ) {
        $this->loanRequestsAction = $loanRequestsAction;
        $this->listUserLoansAction = $listUserLoansAction;
    }

    public function listUserLoans(): Response
    {
        $userId = $_POST['user_id'];

        try {
            $loans = ($this->listUserLoansAction)($userId);
            ob_start();
            require __DIR__ . '/../View/users/loans.php';
            $body = ob_get_clean();
            return new Response($body);
        } catch (Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    public function index(): Response
    {
        try {
            $loanRequests = ($this->loanRequestsAction)();
            ob_start();
            require __DIR__ . '/../View/users/loans.php';
            $body = ob_get_clean();
            return new Response($body);
        } catch (Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }
}
