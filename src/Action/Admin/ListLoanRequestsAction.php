<?php
declare(strict_types=1);

namespace App\Action\Admin;

use App\Domain\Model\Book;

class ListLoanRequestsAction
{
    public function __invoke(): array
    {
        $books = [
            new Book('Test Title 1', 'Test Author 1', '2021', 'book1'),
            new Book('Test Title 2', 'Test Author 2', '2021', 'book2')
        ];

        $loanRequests = [];
        foreach ($books as $book) {
            $loanRequests = array_merge($loanRequests, $book->getAllLoanRequests());
        }

        return $loanRequests;
    }
}
