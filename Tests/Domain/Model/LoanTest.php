<?php
declare(strict_types=1);

namespace Domain\Model;

use App\Domain\Model\Loan;
use App\Domain\ValueObject\LoansDateTimes;
use DateTime;
use PHPUnit\Framework\TestCase;

class LoanTest extends TestCase
{
    private Loan $sut;

    public function test_it_should_loan_creation_and_properties(): void
    {
        $this->assertSame('book1', $this->sut->getBookId());
        $this->assertSame('user1', $this->sut->getUserId());
        $this->assertFalse($this->sut->isReturned());
    }

    public function test_it_should_mark_loan_as_returned(): void
    {
        $returnDate = new DateTime('2023-01-10');
        $this->sut->markAsReturned($returnDate);

        $this->assertTrue($this->sut->isReturned());
        $this->assertSame($returnDate, $this->sut->loanReturnedAt());
    }

    public function test_it_should_return_null_if_loan_not_returned(): void
    {
        $this->assertNull($this->sut->loanReturnedAt());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $borrowDate = new DateTime('2023-01-01');
        $loansDateTimes = new LoansDateTimes($borrowDate);

        $this->sut = new Loan('book1', 'user1', $loansDateTimes);
    }
}
