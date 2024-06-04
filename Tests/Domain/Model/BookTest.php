<?php
declare(strict_types=1);

namespace Domain\Model;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use App\Domain\Model\Book;

class BookTest extends TestCase
{
    public function test_it_should_return_correct_title(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $this->assertSame('Test Title', $book->title());
    }

    public function test_it_should_return_correct_author(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $this->assertSame('Test Author', $book->author());
    }

    public function test_it_should_return_correct_year(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $this->assertSame('2021', $book->year());
    }

    public function test_it_should_return_correct_id_number(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $this->assertSame('ID123', $book->bookId());
    }
    public function test_it_should_return_isAvailable_when_is_available(): void
    {
        $book = new Book('The Title', 'The Author', '2024', 'ID123', true);
        $this->assertTrue($book->isAvailable());
    }

    public function test_it_should_mark_as_unavailable(): void
    {
        $book = new Book('The Title', 'The Author', '2024', 'ID123', true);
        $book->markAsUnavailable();

        $this->assertFalse($book->isAvailable());
    }

    public function test_it_should_mark_as_available(): void
    {
        $book = new Book('The Title', 'The Author', '2024', 'ID123', false);
        $book->markAsAvailable();

        $this->assertTrue($book->isAvailable());
    }

    public function test_it_should_borrow_book(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $book->borrow('user123');
        $this->assertFalse($book->isAvailable());
    }

    public function test_it_should_not_borrow_book_if_already_borrowed(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123', false);
        $book->borrow('user123');
    }

    public function test_it_should_return_book(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $book->borrow('user123');
        $book->returnBook();
        $this->assertTrue($book->isAvailable());
    }

    public function test_it_should_not_return_book_if_already_available(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $book->returnBook();
    }

    public function test_it_should_get_not_returned_loans(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $book->borrow('user123');
        $notReturnedLoans = $book->notReturnedLoans();
        $this->assertCount(1, $notReturnedLoans);
    }

    public function test_it_should_find_loans_by_user(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $book->borrow('user123');
        $loans = $book->findByUser('user123');
        $this->assertCount(1, $loans);
    }

    public function test_it_should_find_active_loan_by_user_and_book(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $book->borrow('user123');
        $loan = $book->findActiveLoanByUserAndBook('user123', 'ID123');
        $this->assertNotNull($loan);
    }

    public function test_it_should_not_find_active_loan_by_user_and_book_if_returned(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $book->borrow('user123');
        $book->returnBook();
        $loan = $book->findActiveLoanByUserAndBook('user123', 'ID123');
        $this->assertNull($loan);
    }
}