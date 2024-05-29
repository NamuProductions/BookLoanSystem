<?php

namespace App\Action\User;

use App\Domain\Repository\LoanRepository;

readonly class ListUserLoansAction
{
    public function __construct(private LoanRepository $loanRepository)
    {
    }

    public function __invoke(string $userId): array
    {
        return $this->loanRepository->findByUser($userId);
    }
}
