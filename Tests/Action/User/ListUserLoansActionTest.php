<?php
declare(strict_types=1);

namespace Action\User;

use App\Action\User\ListUserLoansAction;
use App\Domain\Model\Loan;
use App\Domain\Repository\LoanRepository;
use PHPUnit\Framework\TestCase;

class ListUserLoansActionTest extends TestCase
{
    private LoanRepository $loanRepository;
    private ListUserLoansAction $sut;

    public function test_it_should_return_user_loans(): void
    {
        $user = 'user1';
        $loan1 = new Loan($user, 'ID123', '2023-01-01', '2023-01-15');
        $loan2 = new Loan($user, 'ID124', '2023-01-10', '2023-01-20');

        $this->loanRepository->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([$loan1, $loan2]);

        $result = $this->sut->__invoke($user);

        $this->assertCount(2, $result);
        $this->assertSame([$loan1, $loan2], $result);
    }

    public function test_it_should_return_empty_list_when_user_has_no_loans(): void
    {
        $user = 'user1';

        $this->loanRepository->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([]);

        $result = $this->sut->__invoke($user);

        $this->assertEmpty($result);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loanRepository = $this->createMock(LoanRepository::class);
        $this->sut = new ListUserLoansAction($this->loanRepository);
    }
}
