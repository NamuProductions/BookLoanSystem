<?php
declare(strict_types=1);

namespace Action\User;

use App\Action\User\RequestBookLoanAction;
use App\Domain\Model\Book;
use App\Domain\Model\User;
use App\Domain\Repository\BookRepository;
use App\Domain\Repository\UserRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RequestBookLoanActionTest extends TestCase
{
    private UserRepository $userRepository;
    private BookRepository $bookRepository;
    private RequestBookLoanAction $sut;

    public function test_it_should_request_book_loan(): void
    {
        $user = new User('user1', 'user1@example.com', 'password', 'user');
        $book = new Book('Title1', 'Author1', '2023', 'ID123');

        $this->userRepository
            ->expects($this->once())
            ->method('findByUserName')
            ->with('user1')
            ->willReturn($user);

        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with('ID123')
            ->willReturn($book);

        $this->bookRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Book $savedBook) use ($book) {
                $this->assertSame($book, $savedBook);
                $this->assertFalse($savedBook->isAvailable());
                return true;
            }));

        $this->sut->__invoke('user1', 'ID123');
    }

    public function test_it_should_throw_exception_if_book_not_available(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Book is not available');

        $user = new User('user1', 'user1@example.com', 'password', 'user');
        $book = new Book('Title1', 'Author1', '2023', 'ID123', false);

        $this->userRepository->expects($this->once())
            ->method('findByUserName')
            ->with('user1')
            ->willReturn($user);

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with('ID123')
            ->willReturn($book);

        $this->sut->__invoke('user1', 'ID123');
    }

    public function test_it_should_throw_exception_if_user_not_found(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User not found');

        $this->userRepository->expects($this->once())
            ->method('findByUserName')
            ->with('user1')
            ->willReturn(null);

        $this->sut->__invoke('user1', 'ID123');
    }

    public function test_it_should_throw_exception_if_book_not_found(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Book not found');

        $user = new User('user1', 'user1@example.com', 'password', 'user');

        $this->userRepository->expects($this->once())
            ->method('findByUserName')
            ->with('user1')
            ->willReturn($user);

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with('ID123')
            ->willReturn(null);

        $this->sut->__invoke('user1', 'ID123');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->sut = new RequestBookLoanAction($this->bookRepository, $this->userRepository);
    }
}
