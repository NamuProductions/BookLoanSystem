<?php

declare(strict_types=1);

namespace Action\User;

use App\Action\User\RequestBookLoanAction;
use App\Domain\Model\Book;
use App\Domain\Model\User;
use App\Domain\Repository\BookRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\UserName;
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
            userName: new UserName('user1'),
            password: new Password('Password!1'),
            email: new Email('user1@example.com'),
            fullName: 'user1 and2',
            age: new Age(30)
        );

        $book = new Book(
            title: 'Title1',
            year: new Year(2023),
            author: 'Author1',
            pages: 1234,
            genre: 'Infantil',
            language: 'Català',
            isAvailable: true,
            bookId: '123'
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with(new UserName('user1'))
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
                $this->assertSame($book->Title(), $savedBook->Title());
                $this->assertSame(false, $book->isAvailable());
                return true;
            }));

        $this->sut->__invoke($user->userName(), '123');
    }

    public function test_it_should_throw_exception_if_book_not_available(): void
    {
        $user = new User(
            userName: new UserName('user1'),
            password: new Password('Password1!'),
            email: new Email('user1@example.com'),
            fullName: 'user1 and2',
            age: new Age(30)
        );

        $book = new Book(
            title: 'Title1',
            year: new Year(2023),
            author: 'Author1',
            pages: 1234,
            genre: 'Infantil',
            language: 'Català',
            isAvailable: false,
            bookId: '123'
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with(new UserName('user1'))
            ->willReturn($user);

        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with('123')
            ->willReturn($book);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Book is not available');

        $this->sut->__invoke($user->userName(), '123');
    }

    public function test_it_should_throw_exception_if_user_not_found(): void
    {
        $userName = new UserName('user1');

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with($userName)
            ->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User not found');

        $this->sut->__invoke($userName, '123');
    }

    public function test_it_should_throw_exception_if_book_not_found(): void
    {
        $user = new User(
            userName: new UserName('user1'),
            password: new Password('Password1!'),
            email: new Email('user1@example.com'),
            fullName: 'user1 and2',
            age: new Age(30)
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with(new UserName('user1'))
            ->willReturn($user);

        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with('123')
            ->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Book not found');

        $this->sut->__invoke($user->userName(), '123');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->sut = new RequestBookLoanAction($this->bookRepository, $this->userRepository);
    }
}
