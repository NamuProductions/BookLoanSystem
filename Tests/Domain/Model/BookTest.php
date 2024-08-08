<?php
declare(strict_types=1);

namespace Domain\Model;

use App\Domain\Model\Book;
use App\Domain\Model\User;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\Year;
use App\Domain\ValueObject\UserName;
use App\Domain\ValueObject\Age;
use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    private Book $sut;
    private string $fixedBookId;

    public function test_it_should_return_title(): void
    {
        $this->assertSame('Test Book', $this->sut->title());
    }

    public function test_it_should_return_author(): void
    {
        $this->assertSame('Test Author', $this->sut->author());
    }

    public function test_it_should_return_language(): void
    {
        $this->assertSame('English', $this->sut->language());
    }

    public function test_it_should_return_year(): void
    {
        $this->assertInstanceOf(Year::class, $this->sut->year());
        $this->assertSame(2022, $this->sut->year()->value());
    }

    public function test_it_should_return_book_id(): void
    {
        $this->assertSame($this->fixedBookId, $this->sut->bookId());
    }

    public function test_it_should_be_available_after_creation(): void
    {
        $this->assertTrue($this->sut->isAvailable());
    }

    public function test_it_should_mark_as_unavailable_when_borrowed(): void
    {
        $borrowDate = new DateTime('2023-01-01');

        $this->sut->borrow($this->user, $borrowDate);

        $this->assertFalse($this->sut->isAvailable());
    }

    public function test_it_should_return_loan_details_when_borrowed(): void
    {
        $borrowDate = new DateTime('2023-01-01');
        $this->sut->borrow($this->user, $borrowDate);

        $loan = $this->sut->findAllLoansByUser($this->user->userId())[0];

        $this->assertSame($this->user->userId(), $loan->userId());
        $this->assertSame($this->fixedBookId, $loan->bookId());
        $this->assertEquals($borrowDate->format('Y-m-d'), $loan->loansDateTimes()->loanBorrowedAt()->format('Y-m-d'));
    }

    public function test_it_should_mark_as_available_when_returned(): void
    {
        $borrowDate = new DateTime('2023-01-01');

        $this->sut->borrow($this->user, $borrowDate);
        $this->sut->return($this->user->userId());

        $this->assertTrue($this->sut->isAvailable());
    }

    public function test_it_should_throw_exception_when_returning_unBorrowed_book(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No active loan request found for this user.');

        $this->sut->return($this->user->userId());
    }

    public function test_it_should_find_all_loans_by_user(): void
    {
        $borrowDate1 = new DateTime('2023-01-01');


        $this->sut->borrow($this->user, $borrowDate1);

        $loans = $this->sut->findAllLoansByUser($this->user->userId());

        $this->assertCount(1, $loans);
        $this->assertSame($this->user->userId(), $loans[0]->userId());
        $this->assertSame($this->fixedBookId, $loans[0]->bookId());
        $this->assertEquals($borrowDate1->format('Y-m-d'), $loans[0]->loansDateTimes()->loanBorrowedAt()->format('Y-m-d'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixedBookId = '11111111-1111-1111-1111-111111111111';
        $this->fixedUserId = '22222222-2222-2222-2222-222222222222';

        $this->user = new User(
            userName: new UserName('user1'),
            password: new Password('testPassword1!'),
            email: new Email('user1@test.com'),
            fullName: 'User One',
            age: new Age(25),
            role: 'user',
            userId: $this->fixedUserId
        );

        $this->sut = new Book(
            title: 'Test Book',
            year: new Year(2022),
            author: 'Test Author',
            pages: 300,
            genre: 'Fiction',
            language: 'English',
            isAvailable: true,
            bookId: $this->fixedBookId
        );
    }
}
