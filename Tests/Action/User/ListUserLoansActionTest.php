<?php
declare(strict_types=1);

namespace Action\User;

use App\Action\User\ListUserLoansAction;
use App\Domain\Model\Loan;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\LoansDateTimes;
use DateTime;
use PHPUnit\Framework\TestCase;

class ListUserLoansActionTest extends TestCase
{
    private BookRepository $bookRepository;
    private ListUserLoansAction $sut;

    public function test_it_should_return_user_loans(): void
    {
        $borrowDate = new DateTime('2023-01-01');
        $loansDateTimes = new LoansDateTimes($borrowDate);

        $loan1 = new Loan('book1', 'user1', $loansDateTimes);
        $loan2 = new Loan('book2', 'user1', $loansDateTimes);

        $this->bookRepository->expects($this->once())
            ->method('findAllLoansByUser')
            ->willReturn([$loan1, $loan2]);

        $loans = ($this->sut)('user1');

        $this->assertCount(2, $loans);

        $this->assertSame('user1', $loans[0]->getUserId());
        $this->assertSame('book1', $loans[0]->getBookId());
        $this->assertEquals($borrowDate->format('Y-m-d'), $loans[0]->loansDateTimes()->loanBorrowedAt()->format('Y-m-d'));
        $this->assertEquals($loansDateTimes->LoanMaximumReturnDate()->format('Y-m-d'), $loans[0]->loansDateTimes()->LoanMaximumReturnDate()->format('Y-m-d'));

        $this->assertSame('user1', $loans[1]->getUserId());
        $this->assertSame('book2', $loans[1]->getBookId());
        $this->assertEquals($borrowDate->format('Y-m-d'), $loans[1]->loansDateTimes()->loanBorrowedAt()->format('Y-m-d'));
        $this->assertEquals($loansDateTimes->LoanMaximumReturnDate()->format('Y-m-d'), $loans[1]->loansDateTimes()->LoanMaximumReturnDate()->format('Y-m-d'));
    }

    public function test_it_should_return_empty_list_when_user_has_no_loans(): void
    {
        $this->bookRepository->expects($this->once())
            ->method('findAllLoansByUser')
            ->willReturn([]);

        $loans = ($this->sut)('user1');

        $this->assertEmpty($loans);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sut = new ListUserLoansAction($this->bookRepository);
    }
}
