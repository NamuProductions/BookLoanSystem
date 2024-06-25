<?php
declare(strict_types=1);

namespace Action\Admin;

use App\Action\Admin\LoanRequestsAction;
use App\Domain\ValueObject\LoansDateTimes;
use App\Service\LoanRequestQueryServiceInterface;
use App\Service\LoanRequestDto;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

class LoanRequestsActionTest extends TestCase
{
    private LoanRequestQueryServiceInterface $loanRequestQueryService;
    private LoanRequestsAction $sut;

   public function test_it_should_list_all_loan_requests(): void
    {
        $startDate1 = new DateTime('2023-05-01');
        $startDate2 = new DateTime('2023-05-01');

        $loanRequest1 = new LoanRequestDto('book1', 'Test Title 1', 'user1', 'user1', new LoansDateTimes($startDate1));
        $loanRequest2 = new LoanRequestDto('book2', 'Test Title 2', 'user2', 'user2', new LoansDateTimes($startDate2));

        $this->loanRequestQueryService->method('allLoanRequests')->willReturn([$loanRequest1, $loanRequest2]);

        $result = ($this->sut)();

        $this->assertCount(2, $result);
        $this->assertSame('user1', $result[0]->userId);
        $this->assertSame('book1', $result[0]->bookId);
        $this->assertSame('user2', $result[1]->userId);
        $this->assertSame('book2', $result[1]->bookId);
    }

    public function test_it_should_handle_exception_when_listing_loan_requests(): void
    {
        $this->loanRequestQueryService->method('allLoanRequests')->willThrowException(new Exception('Error retrieving loan requests'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error retrieving loan requests');

        ($this->sut)();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loanRequestQueryService = $this->createMock(LoanRequestQueryServiceInterface::class);
        $this->sut = new LoanRequestsAction($this->loanRequestQueryService);
    }
}
