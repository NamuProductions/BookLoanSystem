<?php

namespace Action\Admin;

use App\Action\Admin\ListReturnRequestsAction;
use App\Domain\Model\Loan;
use App\Domain\Repository\LoanRepository;
use PHPUnit\Framework\TestCase;

class ListReturnRequestsActionTest extends TestCase
{
    private LoanRepository $loanRepository;
    private ListReturnRequestsAction $sut;

    public function test_it_should_list_all_return_requests(): void
    {
        $loan1 = new Loan('user1', 'book1', '2023-01-01', '2023-01-15');
        $loan1->markAsReturned();
        $loan2 = new Loan('user2', 'book2', '2023-02-01', '2023-02-15');
        $loan2->markAsReturned();

        $this->loanRepository->expects($this->once())
            ->method('findAllReturnRequests')
            ->willReturn([$loan1, $loan2]);

        $result = ($this->sut)();

        $this->assertCount(2, $result);
        $this->assertSame($loan1, $result[0]);
        $this->assertSame($loan2, $result[1]);
    }

    public function test_it_should_return_empty_array_if_no_return_requests(): void
    {
        $this->loanRepository->expects($this->once())
            ->method('findAllReturnRequests')
            ->willReturn([]);

        $result = ($this->sut)();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loanRepository = $this->createMock(LoanRepository::class);
        $this->sut = new ListReturnRequestsAction($this->loanRepository);
    }
}
