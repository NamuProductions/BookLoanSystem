<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Domain\Repository\BookRepository;
use App\Service\ActiveLoanQueryServiceInterface;
use InvalidArgumentException;

readonly class MarkBookAsReturnedAction
{
    public function __construct(
        private BookRepository $bookRepository,
        private ActiveLoanQueryServiceInterface $activeLoanQueryService
    ) {}

    public function __invoke(string $userId, string $bookId): void
    {
        $book = $this->bookRepository->findById($bookId);
        if ($book === null) {
            throw new InvalidArgumentException('Book not found.');
        }

        $activeLoan = $this->activeLoanQueryService->findActiveLoan($userId, $bookId);
        if ($activeLoan === null) {
            throw new InvalidArgumentException('No active loan found for this book and user.');
        }

        $book->returnBook();
        $this->bookRepository->save($book);
    }
}
