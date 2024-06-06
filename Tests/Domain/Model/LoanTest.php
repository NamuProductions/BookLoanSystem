<?php
declare(strict_types=1);

namespace Domain\Model;

use App\Domain\Model\Loan;
use DateTime;
use PHPUnit\Framework\TestCase;

class LoanTest extends TestCase
{
    public function test_it_should_loan_creation_and_properties(): void
    {
        $borrowDate = new DateTime('2023-01-01');
        $dueDate = new DateTime('2023-01-15');

        $loan = new Loan('book1', 'user1', $borrowDate, $dueDate);

        $this->assertSame('book1', $loan->getBookId());
        $this->assertSame('user1', $loan->getUserId());
        $this->assertEquals($borrowDate, $loan->getBorrowDate());
        $this->assertEquals($dueDate, $loan->getDueDate());
        $this->assertFalse($loan->isReturned());
    }
}
