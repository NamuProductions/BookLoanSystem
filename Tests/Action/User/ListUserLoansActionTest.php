<?php

namespace Action\User;

use App\Action\User\ListUserLoansAction;
use App\Domain\Model\Loan;
use App\Domain\Repository\LoanRepository;
use PHPUnit\Framework\TestCase;

class ListUserLoansActionTest extends TestCase
{
    private LoanRepository $loanRepository;
    private ListUserLoansAction $sut;

    public function test_it_should_list_user_loans(): void
    {
        $user = 'user1';
        $book = 'ID123';
        $loan = new Loan($user, $book, '2023-01-01', '2023-01-15');

        $this->loanRepository
            ->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([$loan]);

        $result = $this->sut->__invoke('user1');

        $this->assertCount(1, $result);
        $this->assertSame($loan, $result[0]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loanRepository = $this->createMock(LoanRepository::class);
        $this->sut = new ListUserLoansAction($this->loanRepository);
    }
}
