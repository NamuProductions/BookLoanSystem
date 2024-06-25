<?php
declare(strict_types=1);

namespace Domain\Model;

use App\Domain\Model\Loan;
use App\Domain\ValueObject\LoansDateTimes;
use DateTime;
use PHPUnit\Framework\TestCase;

class LoanTest extends TestCase
{
    public function test_it_should_loan_creation_and_properties(): void
    {
        $start = new DateTime('2023-01-01');
        $lastDayOfLoan = new DateTime('2023-01-15');
        $dateRange = new LoansDateTimes($start,$lastDayOfLoan);

        $loan = new Loan('book1', 'user1', $dateRange);

        $this->assertSame('book1', $loan->getBookId());
        $this->assertSame('user1', $loan->getUserId());
        $this->assertEquals($dateRange, $loan->loansDateTimes());
        $this->assertFalse($loan->isReturned());
    }
}
