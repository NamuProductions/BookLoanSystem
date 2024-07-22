<?php

namespace Controller;

use App\Controller\BookController;
use App\Domain\Model\Book;
use App\Domain\Model\User;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use App\Service\SessionManager;
use App\Util\UUID;
use JetBrains\PhpStorm\NoReturn;
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
        $book = new Book('Test Title', new Year(1989), 'Test Author', 123, 'Test Genre', 'English', 1, $this->bookId);
        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with($this->bookId)
            ->willReturn($book);

        ob_start();
        $this->sut->show($this->bookId);
        $output = ob_get_clean();

        $this->assertStringContainsString('Test Title', $output);
        $this->assertStringContainsString('Test Author', $output);
        $this->assertStringContainsString('1989', $output);
    }

    #[NoReturn] public function test_should_allow_user_to_borrow_book_when_book_is_available(): void
    {
        $user = new User('UserName', 'UserPassword1!', 'user@test.com', 'UserName Full', '40', 'user', $this->userId);
        $availableBook = new Book('Test Title', new Year(1989), 'Test Author', 123, 'Test Genre', 'English', 1, $this->bookId);

        $this->sessionManager
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->sessionManager
            ->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with($this->bookId)
            ->willReturn($availableBook);

        $availableBook
            ->expects($this->once())
            ->method('isAvailable')
            ->willReturn(true);

        $availableBook
            ->expects($this->once())
            ->method('borrow')
            ->with($this->userId);

        ob_start();
        $this->sut->borrow($this->bookId);
        ob_get_clean();

        $headers = $this->getHeaders();
        $this->assertContains('Location: /books', $headers);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookId = UUID::generate();
        $this->userId = UUID::generate();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sessionManager = $this->createMock(SessionManager::class);
        $this->sut = new BookController($this->bookRepository, $this->sessionManager);
    }

    private function getHeaders(): array
    {
        return headers_list();
    }
}
