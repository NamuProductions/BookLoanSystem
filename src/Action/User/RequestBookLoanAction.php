<?php
declare(strict_types=1);

namespace App\Action\User;

use App\Domain\Repository\BookRepository;
use App\Domain\Repository\UserRepository;
use DateTime;
use InvalidArgumentException;

readonly class RequestBookLoanAction
{
    public function __construct(
        private BookRepository $bookRepository,
        private UserRepository $userRepository
    ) {}

    public function __invoke(string $userName, string $bookId): void
    {
        $user = $this->userRepository->findByUserName($userName);
        if (!$user) {
            throw new InvalidArgumentException('User not found.');
        }

        $book = $this->bookRepository->findById($bookId);
        if (!$book) {
            throw new InvalidArgumentException('Book not found.');
        }

        if (!$book->isAvailable()) {
            throw new InvalidArgumentException('Book is not available.');
        }

        $book->borrow($user->getUserName(), new DateTime());
        $this->bookRepository->save($book);
    }
}
