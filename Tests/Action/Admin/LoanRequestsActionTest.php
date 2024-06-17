<?php
declare(strict_types=1);

namespace Action\Admin;

use App\Domain\Model\Book;
use App\Domain\ValueObject\Year;
use App\Service\LoanRequestQueryServiceInterface;
use App\Service\MySqlLoanRequestQueryService;
use DateTime;
use Exception;
use PDO;
use PHPUnit\Framework\TestCase;

class LoanRequestsActionTest extends TestCase
{
    private LoanRequestQueryServiceInterface $loanRequestQueryService;
    private MySqlLoanRequestQueryService $sut;

    public function test_it_should_list_all_loan_requests(): void
    {
        $borrowDate = new DateTime('2023-05-01');
        $book1 = new Book('Test Title 1', 'Test Author 1', new Year(2021), 'book1');
        $book2 = new Book('Test Title 2', 'Test Author 2', new Year(2021), 'book2');
        $book1->borrow('user1', $borrowDate);
        $book2->borrow('user2', $borrowDate);

        $this->loanRequestQueryService->method('allLoanRequests')->willReturn([$book1->notReturnedLoans()[0], $book2->notReturnedLoans()[0]]);

        $this->sut = new MySqlLoanRequestQueryService($this->createMock(PDO::class));

        $result = $this->sut->allLoanRequests();

        $this->assertCount(2, $result);
        $this->assertSame('user1', $result[0]->getUserId());
        $this->assertSame('book1', $result[0]->getBookId());
        $this->assertSame('user2', $result[1]->getUserId());
        $this->assertSame('book2', $result[1]->getBookId());
    }

    public function test_it_should_handle_exception_when_listing_loan_requests(): void
    {
        $this->loanRequestQueryService->method('allLoanRequests')->willThrowException(new Exception('Error retrieving loan requests'));

        $this->sut = new MySqlLoanRequestQueryService($this->createMock(PDO::class));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error preparing SQL statement');

        $this->sut->allLoanRequests();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loanRequestQueryService = $this->createMock(LoanRequestQueryServiceInterface::class);
    }
}
