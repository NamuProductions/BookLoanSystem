<?php

namespace Action\User;

use App\Action\User\MarkBookAsReturnedAction;
use App\Domain\Model\Book;
use App\Domain\Model\Loan;
use App\Domain\Repository\BookRepository;
use App\Domain\Repository\LoanRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MarkBookAsReturnedActionTest extends TestCase
{
    private BookRepository $bookRepository;
    private LoanRepository $loanRepository;
    private MarkBookAsReturnedAction $sut;

    public function test_it_should_mark_book_as_returned(): void
    {
        $userId = 'user1';
        $bookId = 'ID123';
        $loan = new Loan($userId, $bookId, '2023-01-01', '2023-01-15');
        $book = new Book('Title1', 'Author1', '2023', $bookId, false);

        $this->loanRepository->expects($this->once())
            ->method('findActiveLoan')
            ->with($userId, $bookId)
            ->willReturn($loan);

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn($book);

        $this->loanRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Loan $savedLoan) use ($loan) {
                return $savedLoan === $loan && $savedLoan->isReturned();
            }));

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

        $this->loanRepository->expects($this->once())
            ->method('findActiveLoan')
            ->with($userId, $bookId)
            ->willReturn(null);

        $this->sut->__invoke($userId, $bookId);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->loanRepository = $this->createMock(LoanRepository::class);
        $this->sut = new MarkBookAsReturnedAction($this->loanRepository, $this->bookRepository);
    }
}
