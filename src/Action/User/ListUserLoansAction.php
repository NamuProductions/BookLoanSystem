<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Domain\Repository\BookRepository;
use App\Domain\Model\Book;

class ListUserLoansAction
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(string $userId): array
    {
        $books = $this->bookRepository->findAll();
        $userLoans = [];

        foreach ($books as $book) {
            $userLoans = array_merge($userLoans, $book->findAllLoansByUser($userId));
        }

        return $userLoans;
    }
}
