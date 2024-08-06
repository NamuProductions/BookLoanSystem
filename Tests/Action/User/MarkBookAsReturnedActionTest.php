<?php
declare(strict_types=1);

namespace Action\User;

use App\Action\User\MarkBookAsReturnedAction;
use App\Domain\Model\Book;
use App\Domain\Model\User;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\UserName;
use App\Domain\ValueObject\Year;
use App\Util\UUID;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use DateTime;

class MarkBookAsReturnedActionTest extends TestCase
{
    private BookRepository $bookRepository;
    private MarkBookAsReturnedAction $sut;

    public function test_it_should_mark_book_as_returned(): void
    {
        $userId = UUID::generate();
        $user = new User(
            userName: new UserName('user1'),
            password: 'testPassword',
            email: 'user1@test.com',
            fullName: 'User One',
            age: new Age(25),
            role: 'user',
            userId: $userId
        );

        $bookId = UUID::generate();
        $borrowDate = new DateTime('2023-01-01');
        $book = new Book('Title1', new Year(2023), 'Author1', 1234 , $bookId, 'Català', true);
        $book->borrow($user, $borrowDate);

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Book $savedBook) use ($book) {
                return $savedBook === $book && $savedBook->isAvailable();
            }));

        $this->sut->__invoke($user->userId(), $bookId);
    }

    public function test_it_should_throw_exception_if_no_active_loan_found(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No active loan request found for this user.');

        $user = new User(new UserName('user1'), 'user1@test.com', 'testPassword', 'user', new Age(30));
        $bookId = UUID::generate();
        $book = new Book('Title1', new Year(2023),'Author1', 1234, $bookId, 'Català');

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn($book);

        $this->sut->__invoke($user->userId(), $bookId);
    }

    public function test_it_should_throw_exception_if_book_not_found(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Book not found.');

        $user = new User(new UserName('user1'), 'user1@test.com', 'testPassword', 'user', new Age(30));
        $bookId = UUID::generate();

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willThrowException(new InvalidArgumentException('Book not found.'));

        $this->sut->__invoke($user->userId(), $bookId);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sut = new MarkBookAsReturnedAction($this->bookRepository);
    }
}
