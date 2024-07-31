<?php
declare(strict_types=1);

namespace Controller;

use App\Action\Admin\LoanRequestsAction;
use App\Action\User\ListUserLoansAction;
use App\Controller\LoanController;
use PHPUnit\Framework\TestCase;
use Exception;

class LoanRequestControllerTest extends TestCase
{
    private LoanRequestsAction $loanRequestsAction;
    private LoanController $sut;

    public function test_it_should_return_loan_requests(): void
    {
        $loanRequests = [
            ['book_id' => 'book1', 'user_id' => 'user1', 'borrow_date' => '2023-01-01', 'due_date' => '2023-01-15'],
            ['book_id' => 'book2', 'user_id' => 'user2', 'borrow_date' => '2023-02-01', 'due_date' => '2023-02-15']
        ];

        $this->loanRequestsAction
            ->method('__invoke')
            ->willReturn($loanRequests);

        $response = $this->sut->index();
        $result = json_decode($response->body(), true);

        $this->assertNotNull($result, 'JSON decode failed: ' . json_last_error_msg());

        $this->assertSame($loanRequests, $result);
    }

    public function test_it_should_handle_exception(): void
    {
        $this->loanRequestsAction
            ->method('__invoke')
            ->willThrowException(new Exception('Error retrieving loan requests'));

        $response = $this->sut->index();
        $result = json_decode($response->body(), true);

        $this->assertNotNull($result, 'JSON decode failed: ' . json_last_error_msg());

        $this->assertArrayHasKey('error', $result);
        $this->assertSame('Error retrieving loan requests', $result['error']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loanRequestsAction = $this->createMock(LoanRequestsAction::class);
        $listUserLoansAction = $this->createMock(ListUserLoansAction::class);
        $this->sut = new LoanController($this->loanRequestsAction, $listUserLoansAction);
    }
}
