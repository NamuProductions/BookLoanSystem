<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Repository\BookRepository;
use App\Domain\Model\Loan;

class ActiveLoanQueryService implements ActiveLoanQueryServiceInterface
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function findActiveLoan(string $userId, string $bookId): ?Loan
    {
        $book = $this->bookRepository->findById($bookId);
        return $book?->findActiveLoanByUserAndBook($userId, $bookId);
    }
}
