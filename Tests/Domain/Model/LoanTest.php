<?php

namespace Domain\Model;

use PHPUnit\Framework\TestCase;
use App\Domain\Model\Loan;

class LoanTest extends TestCase
{
    public function test_loan_creation_and_properties(): void
    {
        $userName = 'testUser';
        $bookTitle = 'testBook';
        $startDate = '2024-05-01';
        $endDate = '2024-05-11';

        $loan = new Loan($userName, $bookTitle, $startDate, $endDate);

        $this->assertSame($userName, $loan->getUserName());
        $this->assertSame($bookTitle, $loan->getBookTitle());
        $this->assertSame($startDate, $loan->getStartDate());
        $this->assertSame($endDate, $loan->getEndDate());
    }
}
