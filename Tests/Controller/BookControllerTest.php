<?php

namespace Controller;

use App\Controller\BookController;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\ValueObject\Year;
use PHPUnit\Framework\TestCase;

class BookControllerTest extends TestCase
{
    private BookRepository $bookRepository;
    private BookController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $userRepository = $this->createMock(UserRepository::class);
        $this->controller = new BookController($this->bookRepository, $userRepository);
    }

    public function test_index(): void
    {
        $books = [
            new Book('Test Title', new Year(1989), 'Test Author', 123, 'Test Genre', 'English', 1, 1)
        ];

        $this->bookRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($books);

        ob_start();
        $this->controller->index();
        $output = ob_get_clean();

        $this->assertStringContainsString('Test Title', $output);
    }

    public function test_show_book_not_found(): void
    {
        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with('1')
            ->willReturn(null);

        ob_start();
        $this->controller->show(1);
        $output = ob_get_clean();

        $this->assertStringContainsString('Book not found', $output);
    }
//
//    public function test_show_book_found(): void
//    {
//        $book = new Book('Test Title', new DateTime(), 'Test Author', 123, 'Test Genre', 'English', 1, 1);
//
//        $this->bookRepository
//            ->expects($this->once())
//            ->method('findById')
//            ->with('1')
//            ->willReturn($book);
//
//        ob_start();
//        $this->controller->show(1);
//        $output = ob_get_clean();
//
//        $this->assertStringContainsString('Test Title', $output);
//    }
//
//    public function test_borrow_book_not_available(): void
//    {
//        $book = $this->createMock(Book::class);
//        $book->method('isAvailable')->willReturn(false);
//
//        $this->bookRepository
//            ->expects($this->once())
//            ->method('findById')
//            ->with('1')
//            ->willReturn($book);
//
//        session_start();
//        $_SESSION['user'] = 'testUser';
//
//        $this->userRepository
//            ->expects($this->once())
//            ->method('findByUserName')
//            ->with('testUser')
//            ->willReturn(new User('testUser', 'testuser@example.com', 'testPassword', 'user'));
//
//        ob_start();
//        $this->controller->borrow(1);
//        $output = ob_get_clean();
//
//        $this->assertStringContainsString('Book is not available', $output);
//    }
//
//    public function test_borrow_book_success(): void
//    {
//        $book = $this->createMock(Book::class);
//        $book->method('isAvailable')->willReturn(true);
//
//        $this->bookRepository
//            ->expects($this->once())
//            ->method('findById')
//            ->with('1')
//            ->willReturn($book);
//
//        session_start();
//        $_SESSION['user'] = 'testUser';
//
//        $user = new User('testUser', 'testuser@example.com', 'testPassword', 'user');
//
//        $this->userRepository
//            ->expects($this->once())
//            ->method('findByUserName')
//            ->with('testUser')
//            ->willReturn($user);
//
//        $book->expects($this->once())
//            ->method('borrow')
//            ->with($user, $this->isInstanceOf(DateTime::class));
//
//        $this->bookRepository
//            ->expects($this->once())
//            ->method('save')
//            ->with($book);
//
//        ob_start();
//        $this->controller->borrow(1);
//        ob_end_clean();
//
//        $headers = xdebug_get_headers();
//        $this->assertContains('Location: /books', $headers);
//    }
//
//    public function test_return_user_not_logged_in(): void
//    {
//        ob_start();
//        $this->controller->return(1);
//        $output = ob_get_clean();
//
//        $this->assertStringContainsString('User not logged in', $output);
//    }
//
//    public function test_return_user_not_found(): void
//    {
//        session_start();
//        $_SESSION['userId'] = '1';
//
//        $this->userRepository
//            ->expects($this->once())
//            ->method('findById')
//            ->with('1')
//            ->willReturn(null);
//
//        ob_start();
//        $this->controller->return(1);
//        $output = ob_get_clean();
//
//        $this->assertStringContainsString('User not found', $output);
//    }
//
//    public function test_return_book_not_found(): void
//    {
//        session_start();
//        $_SESSION['userId'] = '1';
//
//        $user = new User('testUser', 'testuser@example.com', 'testPassword', 'user');
//
//        $this->userRepository
//            ->expects($this->once())
//            ->method('findById')
//            ->with('1')
//            ->willReturn($user);
//
//        $this->bookRepository
//            ->expects($this->once())
//            ->method('findById')
//            ->with('1')
//            ->willReturn(null);
//
//        ob_start();
//        $this->controller->return(1);
//        $output = ob_get_clean();
//
//        $this->assertStringContainsString('Book not found', $output);
//    }
//
//    public function test_return_book_success(): void
//    {
//        session_start();
//        $_SESSION['userId'] = '1';
//
//        $user = new User('testUser', 'testuser@example.com', 'testPassword', 'user');
//
//        $this->userRepository
//            ->expects($this->once())
//            ->method('findById')
//            ->with('1')
//            ->willReturn($user);
//
//        $book = $this->createMock(Book::class);
//        $this->bookRepository
//            ->expects($this->once())
//            ->method('findById')
//            ->with('1')
//            ->willReturn($book);
//
//        $book->expects($this->once())
//            ->method('returnBook')
//            ->with('1');
//
//        $this->bookRepository
//            ->expects($this->once())
//            ->method('save')
//            ->with($book);
//
//        ob_start();
//        $this->controller->return(1);
//        $output = ob_get_clean();
//
//        $headers = xdebug_get_headers();
//        $this->assertContains('Location: /books', $headers);
//        $this->assertStringContainsString('Book returned successfully', $output);
//    }
}
