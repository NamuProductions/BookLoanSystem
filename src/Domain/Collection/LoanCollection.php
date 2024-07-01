<?php
declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Model\Loan;

class LoanCollection
{
    private array $loans = [];

    public function addLoan(Loan $loan): void
    {
        $this->loans[] = $loan;
    }

//    public function getNotReturnedLoans(): array
//    {
//        return array_filter($this->loans, fn($loan) => !$loan->isReturned());
//    }
//
//    public function getReturnedLoans(): array
//    {
//        return array_filter($this->loans, fn($loan) => $loan->isReturned());
//    }

    public function findAllLoansByUser(string $userId): array
    {
        return array_filter($this->loans, fn($loan) => $loan->getUserId() === $userId);
    }

    public function findActiveLoanByUser(string $userId): ?Loan
    {
        foreach ($this->loans as $loan) {
            if ($loan->getUserId() === $userId && !$loan->isReturned()) {
                return $loan;
            }
        }
        return null;
    }
}
