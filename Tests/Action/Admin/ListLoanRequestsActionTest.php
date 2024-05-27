<?php

namespace Action\Admin;


use App\Action\Admin\ListLoanRequestsAction;
use App\Domain\Model\Loan;
use App\Domain\Repository\LoanRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;


class ListLoanRequestsActionTest extends TestCase
{
    private LoanRepository $loanRepository;
    private ListLoanRequestsAction $sut;

    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->loanRepository = $this->createMock(LoanRepository::class);
        } catch (Exception) {
        }
        $this->sut = new ListLoanRequestsAction($this->loanRepository);
    }

    public function test_it_should_list_all_loan_requests(): void
    {
        $loanRequests = [
            new Loan('user1', 'book1', '2024-05-20', '2024-05-30'),
            new Loan('user2', 'book2', '2024-05-19', '2024-05-29')
        ];

        $this->loanRepository
            ->expects($this->once())
            ->method('findAllLoanRequests')
            ->willReturn($loanRequests);

        $result = $this->sut->__invoke();

        $this->assertSame($loanRequests, $result);
    }
}