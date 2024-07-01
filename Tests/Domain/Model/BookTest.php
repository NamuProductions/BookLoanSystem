<?php
declare(strict_types=1);

namespace Domain\Model;

use App\Domain\Model\Book;
use App\Domain\ValueObject\Year;
use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    private Book $sut;

    public function test_it_should_return_title(): void
    {
        $this->assertSame('Test Book', $this->sut->title());
    }

    public function test_it_should_return_author(): void
    {
        $this->assertSame('Test Author', $this->sut->author());
    }

    public function test_it_should_return_language(): void
    {
        $this->assertSame('English', $this->sut->language());
    }

    public function test_it_should_return_year(): void
    {
        $this->assertInstanceOf(Year::class, $this->sut->year());
        $this->assertSame(2022, $this->sut->year()->getValue());
    }

    public function test_it_should_return_book_id(): void
    {
        $this->assertSame('book1', $this->sut->bookId());
    }

    public function test_it_should_be_available_after_creation(): void
    {
        $this->assertTrue($this->sut->isAvailable());
    }

    public function test_it_should_mark_as_unavailable_when_borrowed(): void
    {
        $borrowDate = new DateTime('2023-01-01');
        $this->sut->borrow('user1', $borrowDate);

        $this->assertFalse($this->sut->isAvailable());
    }

    public function test_it_should_return_loan_details_when_borrowed(): void
    {
        $borrowDate = new DateTime('2023-01-01');
        $loan = $this->sut->borrow('user1', $borrowDate);

        $this->assertSame('user1', $loan->getUserId());
        $this->assertSame('book1', $loan->getBookId());
        $this->assertEquals($borrowDate->format('Y-m-d'), $loan->loansDateTimes()->loanBorrowedAt()->format('Y-m-d'));
    }

    public function test_it_should_mark_as_available_when_returned(): void
    {
        $borrowDate = new DateTime('2023-01-01');
        $this->sut->borrow('user1', $borrowDate);
        $this->sut->returnBook('user1');

        $this->assertTrue($this->sut->isAvailable());
    }

    public function test_it_should_throw_exception_when_returning_unBorrowed_book(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No active loan found for this book and user.');

        $this->sut->returnBook('user1');
    }

    public function test_it_should_find_all_loans_by_user(): void
    {
        $borrowDate1 = new DateTime('2023-01-01');

        $this->sut->borrow('user1', $borrowDate1);

        $loans = $this->sut->findAllLoansByUser('user1');

        $this->assertCount(1, $loans);
        $this->assertSame('user1', $loans[0]->getUserId());
        $this->assertSame('book1', $loans[0]->getBookId());
        $this->assertEquals($borrowDate1->format('Y-m-d'), $loans[0]->loansDateTimes()->loanBorrowedAt()->format('Y-m-d'));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new Book('Test Book', 'Test Author', 'English', new Year(2022), 'book1');
    }
}
