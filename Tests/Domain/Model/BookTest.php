<?php
declare(strict_types=1);

namespace Domain\Model;

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
        $this->assertSame('ID123', $book->idNumber());
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
}