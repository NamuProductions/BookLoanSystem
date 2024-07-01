<?php
declare(strict_types=1);

namespace Domain\Collection;

use App\Domain\Collection\LoanCollection;
use App\Domain\Model\Loan;
use PHPUnit\Framework\TestCase;

class LoanCollectionTest extends TestCase
{
    private LoanCollection $sut;

    public function test_it_should_add_a_loan(): void
    {
        $loan = $this->createMock(Loan::class);
        $this->sut->addLoan($loan);

        $loans = $this->sut->findAllLoansByUser($loan->getUserId());
        $this->assertCount(1, $loans);
        $this->assertSame($loan, $loans[0]);
    }

    public function test_it_should_find_all_loans_by_user(): void
    {
        $loan1 = $this->createMock(Loan::class);
        $loan1->method('getUserId')->willReturn('user1');

        $loan2 = $this->createMock(Loan::class);
        $loan2->method('getUserId')->willReturn('user2');

        $this->sut->addLoan($loan1);
        $this->sut->addLoan($loan2);

        $userLoans = $this->sut->findAllLoansByUser('user1');
        $this->assertCount(1, $userLoans);
        $this->assertSame($loan1, $userLoans[0]);
    }

    public function test_it_should_find_active_loan_by_user(): void
    {
        $loan = $this->createMock(Loan::class);
        $loan->method('getUserId')->willReturn('user1');
        $loan->method('isReturned')->willReturn(false);

        $this->sut->addLoan($loan);

        $activeLoan = $this->sut->findActiveLoanByUser('user1');
        $this->assertSame($loan, $activeLoan);
    }

    public function test_it_should_return_null_if_no_active_loan_found_by_user(): void
    {
        $loan = $this->createMock(Loan::class);
        $loan->method('getUserId')->willReturn('user1');
        $loan->method('isReturned')->willReturn(true);

        $this->sut->addLoan($loan);

        $activeLoan = $this->sut->findActiveLoanByUser('user1');
        $this->assertNull($activeLoan);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new LoanCollection();
    }
}
