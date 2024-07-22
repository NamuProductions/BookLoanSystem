<?php

namespace Controller;

use App\Controller\BookController;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use App\Service\SessionManager;
use App\Util\UUID;
use PHPUnit\Framework\TestCase;

class BookControllerTest extends TestCase
{
    private BookRepository $bookRepository;
    private BookController $sut;
    private string $bookId;

    public function test_should_display_all_books_on_index_page(): void
    {
        $books = [
            new Book('Test Title', new Year(1989), 'Test Author', 123, 'Test Genre', 'English', 1, $this->bookId)
        ];

        $this->bookRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($books);

        ob_start();
        $this->sut->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('Test Title', $output);
    }

    public function test_should_display_book_details_when_show_is_called_with_valid_bookId(): void
    {


    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->bookId = UUID::generate();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $sessionManager = $this->createMock(SessionManager::class);
        $this->sut = new BookController($this->bookRepository, $sessionManager);
    }
}
