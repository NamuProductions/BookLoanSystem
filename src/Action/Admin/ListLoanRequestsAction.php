<?php
declare(strict_types=1);

namespace App\Action\Admin;

use App\Domain\Repository\BookRepository;
use Exception;

readonly class ListLoanRequestsAction
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(): array
    {
        try {
            $allBooks = $this->bookRepository->findAll();
            $loanRequests = [];
            foreach ($allBooks as $book) {
                $loanRequests = array_merge($loanRequests, $book->notReturnedLoans());
            }
            return $loanRequests;
        } catch (Exception) {
            throw new Exception('Error retrieving loan requests');
        }
    }
}
