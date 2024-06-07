<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Model\Loan;

interface ActiveLoanQueryServiceInterface
{
    public function findActiveLoan(string $userId, string $bookId): ?Loan;
}
