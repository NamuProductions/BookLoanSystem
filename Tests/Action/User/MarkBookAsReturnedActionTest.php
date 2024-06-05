<?php
declare(strict_types=1);

namespace Action\User;

use App\Action\User\MarkBookAsReturnedAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use DateTime;

class MarkBookAsReturnedActionTest extends TestCase
{
    private BookRepository $bookRepository;
    private MarkBookAsReturnedAction $sut;

    public function test_it_should_mark_book_as_returned(): void
    {
        $userId = 'user1';
        $bookId = 'ID123';
        $borrowDate = new DateTime('2023-01-01');
        $book = new Book('Title1', 'Author1', new Year(2023), $bookId, true);
        $book->borrow($userId, $borrowDate);

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Book $savedBook) use ($book) {
                return $savedBook === $book && $savedBook->isAvailable();
            }));

        $this->sut->__invoke($userId, $bookId);
    }

    public function test_it_should_throw_exception_if_no_active_loan_found(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No active loan found for this book and user.');

        $userId = 'user1';
        $bookId = 'ID123';
        $book = new Book('Title1', 'Author1', new Year(2023), $bookId, true);

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn($book);

        $this->sut->__invoke($userId, $bookId);
    }

    public function test_it_should_throw_exception_if_book_not_found(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Book not found.');

        $userId = 'user1';
        $bookId = 'ID123';

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn(null);

        $this->sut->__invoke($userId, $bookId);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sut = new MarkBookAsReturnedAction($this->bookRepository);
    }
}
