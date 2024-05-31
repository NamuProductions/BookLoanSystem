<?php
declare(strict_types=1);

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

        $this->assertSame($userName, $loan->getUser());
        $this->assertSame($bookTitle, $loan->getBook());
        $this->assertSame($startDate, $loan->getLoanDate());
        $this->assertSame($endDate, $loan->getReturnDate());
    }
}
