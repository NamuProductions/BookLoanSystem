<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Domain\Repository\BookRepository;

readonly class SearchBooksAction
{
public function __construct(private BookRepository $bookRepository)
{

}
    public function __invoke(string $query): array
    {
        return $this->bookRepository->search($query);
    }
}