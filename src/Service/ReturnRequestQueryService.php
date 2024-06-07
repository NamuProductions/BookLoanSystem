<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Repository\BookRepository;

readonly class ReturnRequestQueryService implements ReturnRequestQueryServiceInterface
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function returnRequests(): array
    {
        $books = $this->bookRepository->findAll();
        $returnRequests = [];
        foreach ($books as $book) {
            $returnRequests = array_merge($returnRequests, $book->returnedLoans());
        }
        return $returnRequests;
    }
}
