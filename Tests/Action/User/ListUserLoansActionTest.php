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
        $book = new Book('Test Title', 'Test Author', new Year(2021), 'book1');
        $book->borrow('user1', $borrowDate);

        $loans = $book->findByUser('user1');

        $this->assertCount(1, $loans);
        $this->assertSame('user1', $loans[0]->getUserId());
        $this->assertSame($book->bookId(), $loans[0]->getBookId());
        $this->assertEquals($borrowDate->format('Y-m-d'), $loans[0]->getBorrowDate()->format('Y-m-d'));
        $this->assertEquals($borrowDate->modify('+14 days')->format('Y-m-d'), $loans[0]->getDueDate()->format('Y-m-d'));
    }

    public function test_it_should_return_empty_list_when_user_has_no_loans(): void
    {
        $book = new Book('Test Title', 'Test Author', new Year(2021), 'book1');
        $loans = $book->findByUser('user1');

        $this->assertEmpty($loans);
    }

    public function test_it_should_mark_book_as_returned(): void
    {
        $borrowDate = new DateTime('2023-01-01');
        $book = new Book('Test Title', 'Test Author', new Year(2021), 'book1');
        $book->borrow('user1', $borrowDate);

        $loans = $book->findByUser('user1');
        $this->assertCount(1, $loans);
        $this->assertFalse($loans[0]->isReturned());

        $book->returnBook();

        $loans = $book->findByUser('user1');
        $this->assertCount(1, $loans);
        $this->assertTrue($loans[0]->isReturned());
        $this->assertInstanceOf(DateTime::class, $loans[0]->getReturnDate());
    }
}
