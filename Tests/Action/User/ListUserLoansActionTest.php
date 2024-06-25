<?php
declare(strict_types=1);

namespace Action\User;

use App\Domain\Model\Book;
use App\Domain\ValueObject\Year;
use DateTime;
use PHPUnit\Framework\TestCase;

class ListUserLoansActionTest extends TestCase
{
    public function test_it_should_return_user_loans(): void
    {
        $borrowDate = new DateTime('2023-01-01');
        $dueDate = (clone $borrowDate)->modify('+14 days');

        $book = new Book('Test Title', 'Test Author', 'Català', new Year(2021), 'book1');
        $book->borrow('user1', $borrowDate);

        $loans = $book->findAllLoansByUser('user1');

        $this->assertCount(1, $loans);
        $this->assertSame('user1', $loans[0]->getUserId());
        $this->assertSame($book->bookId(), $loans[0]->getBookId());
        $this->assertEquals($borrowDate->format('Y-m-d'), $loans[0]->loansDateTimes()->loanBorrowedAt()->format('Y-m-d'));
        $this->assertEquals($dueDate->format('Y-m-d'), $loans[0]->loansDateTimes()->loanMaximumReturnDate()->format('Y-m-d'));
    }

    public function test_it_should_return_empty_list_when_user_has_no_loans(): void
    {
        $book = new Book('Test Title', 'Test Author', 'Català', new Year(2021), 'book1');
        $loans = $book->findAllLoansByUser('user1');

        $this->assertEmpty($loans);
    }
   }
