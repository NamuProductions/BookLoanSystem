<?php
namespace Action\User;

use App\Action\User\RequestBookLoanAction;
use App\Domain\Model\Book;
use App\Domain\Model\Loan;
use App\Domain\Model\User;
use App\Domain\Repository\BookRepository;
use App\Domain\Repository\LoanRepository;
use App\Domain\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class RequestBookLoanActionTest extends TestCase
{
    private UserRepository $userRepository;
    private BookRepository $bookRepository;
    private LoanRepository $loanRepository;
    private RequestBookLoanAction $sut;

    public function test_it_should_request_book_loan(): void
    {
        $user = new User('user1', 'user1@example.com', 'password', 'user');
        $book = new Book('Title1', 'Author1', '2023', 'ID123');

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with('user1')
            ->willReturn($user);

        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with('ID123')
            ->willReturn($book);

        $this->loanRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Loan $loan) use ($user, $book) {
                return $loan->getUser() === $user && $loan->getBook() === $book;
            }));

        $this->bookRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Book $savedBook) use ($book) {
                return $savedBook === $book && !$savedBook->isAvailable();
            }));

        $this->sut->__invoke('user1', 'ID123');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->loanRepository = $this->createMock(LoanRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->sut = new RequestBookLoanAction($this->bookRepository, $this->loanRepository, $this->userRepository);
    }

}