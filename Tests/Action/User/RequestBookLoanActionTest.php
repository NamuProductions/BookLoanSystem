<?php
declare(strict_types=1);

namespace Action\User;

use App\Action\User\RequestBookLoanAction;
use App\Domain\Model\Book;
use App\Domain\Model\User;
use App\Domain\Repository\BookRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\ValueObject\Year;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RequestBookLoanActionTest extends TestCase
{
    private UserRepository $userRepository;
    private BookRepository $bookRepository;
    private RequestBookLoanAction $sut;

    public function test_it_should_request_book_loan(): void
    {
        $user = new User(
            userName: 'user1',
            password: 'password',
            email: 'user1@example.com',
            fullName: 'user1 and2');

        $book = new Book(
            title: 'Title1',
            year: new Year(2023),
            author: 'Author1',
            pages: 1234,
            genre: 'Infantil',
            language: 'Català',
            isAvailable: true,
            bookId: '123');

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with('user1')
            ->willReturn($user);

        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with('123')
            ->willReturn($book);

        $this->bookRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Book $savedBook) use ($book) {
                $this->assertSame($book, $savedBook);
                $this->assertFalse($savedBook->isAvailable());
                return true;
            }));

        $this->sut->__invoke('user1', '123');
    }

    public function test_it_should_throw_exception_if_book_not_available(): void
    {
        $user = new User(
            userName: 'user1',
            password: 'password',
            email: 'user1@example.com',
            fullName: 'user1 and2');

        $book = new Book(
            title: 'Title1',
            year: new Year(2023),
            author: 'Author1',
            pages: 1234,
            genre: 'Infantil',
            language: 'Català',
            isAvailable: false,
            bookId: '1');

        $this->userRepository->expects($this->once())
            ->method('findByUserName')
            ->with('user1')
            ->willReturn($user);

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with('123')
            ->willReturn($book);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Book is already borrowed');

        $this->sut->__invoke('user1', '123');
    }

    public function test_it_should_throw_exception_if_user_not_found(): void
    {
        $this->userRepository->expects($this->once())
            ->method('findByUserName')
            ->with('user1')
            ->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User not found');

        $this->sut->__invoke('user1', '123');
    }

    public function test_it_should_throw_exception_if_book_not_found(): void
    {

        $user = new User(
            userName: 'user1',
            password: 'password',
            email: 'user1@example.com',
            fullName: 'user1 and2');

        $this->userRepository->expects($this->once())
            ->method('findByUserName')
            ->with('user1')
            ->willReturn($user);

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with('123')
            ->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Book not found');

        $this->sut->__invoke('user1', '123');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->sut = new RequestBookLoanAction($this->bookRepository, $this->userRepository);
    }
}
