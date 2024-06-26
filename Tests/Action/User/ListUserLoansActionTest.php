<?php
declare(strict_types=1);

namespace Action\User;

use App\Action\User\ListUserLoansAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use DateTime;
use PHPUnit\Framework\TestCase;

class ListUserLoansActionTest extends TestCase
{
    private BookRepository $bookRepository;
    private ListUserLoansAction $sut;

    public function test_it_should_return_user_loans(): void
    {
        $borrowDate = new DateTime('2023-01-01');
        $dueDate = (clone $borrowDate)->modify('+14 days');
        $book = new Book('Test Title', 'Test Author', 'Català', new Year(2021), 'book1');
        $book->borrow('user1', $borrowDate);

        $this->bookRepository->expects($this->once())
            ->method('findAllLoansByUser')
            ->with('user1')
            ->willReturn([$book]);

        $loans = $this->sut->execute('user1');

        $this->assertCount(1, $loans);
        $this->assertSame('user1', $loans[0]->getUserId());
        $this->assertSame($book->bookId(), $loans[0]->getBookId());
        $this->assertEquals($borrowDate->format('Y-m-d'), $loans[0]->loansDateTimes()->loanBorrowedAt()->format('Y-m-d'));
        $this->assertEquals($dueDate->format('Y-m-d'), $loans[0]->loansDateTimes()->loanMaximumReturnDate()->format('Y-m-d'));
    }

    public function test_it_should_return_empty_list_when_user_has_no_loans(): void
    {
        $this->bookRepository->expects($this->once())
            ->method('findAllLoansByUser')
            ->with('user1')
            ->willReturn([]);

        $loans = $this->sut->execute('user1');

        $this->assertEmpty($loans);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sut = new ListUserLoansAction($this->bookRepository);
    }
}
