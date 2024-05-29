<?php

namespace Domain\Model;

use PHPUnit\Framework\TestCase;
use App\Domain\Model\Book;

class BookTest extends TestCase
{
    public function test_it_should_return_correct_title(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $this->assertSame('Test Title', $book->getTitle());
    }

    public function test_it_should_return_correct_author(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $this->assertSame('Test Author', $book->getAuthor());
    }

    public function test_it_should_return_correct_year(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $this->assertSame('2021', $book->getYear());
    }

    public function test_it_should_return_correct_id_number(): void
    {
        $book = new Book('Test Title', 'Test Author', '2021', 'ID123');
        $this->assertSame('ID123', $book->getIdNumber());
    }
}