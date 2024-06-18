<?php
declare(strict_types=1);

namespace Controller;

use App\Action\Admin\LoanRequestsAction;
use App\Controller\LoanRequestController;
use PHPUnit\Framework\TestCase;
use Exception;

class LoanRequestControllerTest extends TestCase
{
    private LoanRequestsAction $loanRequestsAction;
    private LoanRequestController $sut;


    public function test_it_should_return_loan_requests(): void
    {
        $loanRequests = [
            ['book_id' => 'book1', 'user_id' => 'user1', 'borrow_date' => '2023-01-01', 'due_date' => '2023-01-15'],
            ['book_id' => 'book2', 'user_id' => 'user2', 'borrow_date' => '2023-02-01', 'due_date' => '2023-02-15']
        ];

        $this->loanRequestsAction
            ->method('__invoke')
            ->willReturn($loanRequests);

        $result = $this->sut->index();

        $this->assertSame($loanRequests, $result);
    }

    public function test_it_should_handle_exception(): void
    {
        $this->loanRequestsAction
            ->method('__invoke')
            ->willThrowException(new Exception('Error retrieving loan requests'));

        $result = $this->sut->index();

        $this->assertArrayHasKey('error', $result);
        $this->assertSame('Error retrieving loan requests', $result['error']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loanRequestsAction = $this->createMock(LoanRequestsAction::class);
        $this->sut = new LoanRequestController($this->loanRequestsAction);
    }
}

