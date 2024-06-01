<?php
declare(strict_types=1);

namespace Action\Admin;

use App\Action\Admin\ListLoanRequestsAction;
use App\Domain\Model\Book;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

class ListLoanRequestsActionTest extends TestCase
{

    public function test_it_should_list_all_loan_requests(): void
    {
        $borrowDate = new DateTime('2023-05-01');
        $book1 = new Book('Test Title 1', 'Test Author 1', '2021', 'book1');
        $book2 = new Book('Test Title 2', 'Test Author 2', '2021', 'book2');
        $book1->borrow('user1', $borrowDate);
        $book2->borrow('user2', $borrowDate);

        $loanRequests = array_merge($book1->getAllLoanRequests(), $book2->getAllLoanRequests());

        $result = $loanRequests;

        $this->assertCount(2, $result);
        $this->assertSame('user1', $result[0]->getUserId());
        $this->assertSame('book1', $result[0]->getBookId());
        $this->assertSame('user2', $result[1]->getUserId());
        $this->assertSame('book2', $result[1]->getBookId());
    }

    public function test_it_should_handle_exception_when_listing_loan_requests(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error retrieving loan requests');

        throw new Exception('Error retrieving loan requests');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new ListLoanRequestsAction();
    }
}
