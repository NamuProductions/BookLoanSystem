<?php
declare(strict_types=1);

namespace App\Action\Admin;

use App\Domain\Repository\BookRepository;
use Exception;

readonly class ListReturnRequestsAction
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(): array
    {
        try {
            $books = $this->bookRepository->findAll();
            $returnRequests = [];
            foreach ($books as $book) {
                $returnRequests = array_merge($returnRequests, $book->returnedLoans());
            }
            return $returnRequests;
        } catch (Exception) {
            throw new Exception('Error retrieving return requests');
        }
    }
}
