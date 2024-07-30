<?php

declare(strict_types=1);

namespace Controller;

use App\Action\Admin\AddNewBookAction;
use App\Action\User\ListAvailableBooksAction;
use App\Action\User\MarkBookAsReturnedAction;
use App\Action\User\RequestBookLoanAction;
use App\Action\User\SearchBooksAction;
use App\Controller\BookController;
use App\Controller\NotAuthenticatedException;
use App\Domain\Model\Book;
use App\Domain\Model\User;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use App\Service\SessionManager;
use App\Util\UUID;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BookControllerTest extends TestCase
{
    private BookRepository $bookRepository;
    private SessionManager $sessionManager;
    private RequestBookLoanAction $requestBookLoanAction;
    private MarkBookAsReturnedAction $markBookAsReturnedAction;
    private BookController $sut;
    private string $bookId;
    private string $userId;

    public function test_should_display_all_books_on_index_page(): void
    {
        $books = [
            new Book('Test Title', new Year(1989), 'Test Author', 123, 'Test Genre', 'English', true, $this->bookId)
        ];

        $this->bookRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($books);

        $response = $this->sut->index();
        $output = $response->body();

        $this->assertStringContainsString('Test Title', $output);
    }

    public function test_should_display_book_details_when_show_is_called_with_valid_bookId(): void
    {
        $book = new Book('Test Title', new Year(1989), 'Test Author', 123, 'Test Genre', 'English', true, $this->bookId);
        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with($this->bookId)
            ->willReturn($book);

        $response = $this->sut->show($this->bookId);
        $output = $response->body();

        $this->assertStringContainsString('Test Title', $output);
        $this->assertStringContainsString('Test Author', $output);
        $this->assertStringContainsString('1989', $output);
    }

    public function test_should_allow_user_to_borrow_book_when_book_is_available(): void
    {
        $user = $this->createMock(User::class);
        $user->method('userName')->willReturn('user123');

        $this->sessionManager
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->sessionManager
            ->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $this->requestBookLoanAction
            ->expects($this->once())
            ->method('__invoke')
            ->with($user->userName(), $this->bookId);

        $response = $this->sut->borrow($this->bookId);
        $headers = $response->headers();

        $this->assertEquals(302, $response->StatusCode());
        $this->assertStringContainsString('/books', $headers['Location']);
    }

    public function test_should_allow_user_to_return_book_when_book_is_borrowed(): void
    {
        $user = $this->createMock(User::class);
        $user->method('userId')->willReturn('user123');

        $this->sessionManager
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->sessionManager
            ->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $this->markBookAsReturnedAction
            ->expects($this->once())
            ->method('__invoke')
            ->with($user->userId(), $this->bookId);

        $response = $this->sut->return($this->bookId);
        $headers = $response->headers();

        $this->assertEquals(302, $response->StatusCode());
        $this->assertStringContainsString('/books', $headers['Location']);
    }

    public function test_should_return_error_when_book_not_found(): void
    {
        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with($this->bookId)
            ->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Book not found');

        $this->sut->show($this->bookId);
    }

    public function test_should_return_error_when_not_authenticated_on_borrow(): void
    {
        $this->sessionManager
            ->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(false);

        $this->expectException(NotAuthenticatedException::class);

        $this->sut->borrow($this->bookId);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookId = UUID::generate();
        $this->userId = UUID::generate();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sessionManager = $this->createMock(SessionManager::class);
        $this->addNewBookAction = $this->createMock(AddNewBookAction::class);
        $this->listAvailableBookAction = $this->createMock(ListAvailableBooksAction::class);
        $this->searchBookAction = $this->createMock(SearchBooksAction::class);
        $this->requestBookLoanAction = $this->createMock(RequestBookLoanAction::class);
        $this->markBookAsReturnedAction = $this->createMock(MarkBookAsReturnedAction::class);
        $this->sut = new BookController(
            $this->bookRepository,
            $this->sessionManager,
            $this->addNewBookAction,
            $this->listAvailableBookAction,
            $this->searchBookAction,
            $this->requestBookLoanAction,
            $this->markBookAsReturnedAction,
        );
    }
}
