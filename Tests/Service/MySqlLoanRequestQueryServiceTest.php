<?php
declare(strict_types=1);

namespace Service;

use App\Service\MySqlLoanRequestQueryService;
use Exception;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use App\Service\LoanRequestDto;

class MySqlLoanRequestQueryServiceTest extends TestCase
{
    private PDO $pdoMock;
    private PDOStatement $statementMock;
    private MySqlLoanRequestQueryService $sut;

    public function test_it_should_list_all_loan_requests(): void
    {
        $this->pdoMock->method('prepare')->willReturn($this->statementMock);
        $this->statementMock->method('execute')->willReturn(true);
        $this->statementMock->method('fetch')
            ->willReturnOnConsecutiveCalls(
                [
                    'book_id' => 'book1',
                    'title' => 'Test Title 1',
                    'user_name' => 'user1',
                    'user_id' => 'user1',
                    'borrow_at' => '2023-05-01',
                    'return_at' => null
                ],
                [
                    'book_id' => 'book2',
                    'title' => 'Test Title 2',
                    'user_name' => 'user2',
                    'user_id' => 'user2',
                    'borrow_at' => '2023-05-01',
                    'return_at' => null
                ],
                false
            );

        $result = $this->sut->allLoanRequests();

        $this->assertCount(2, $result);

        $this->assertInstanceOf(LoanRequestDto::class, $result[0]);
        $this->assertSame('user1', $result[0]->userId);
        $this->assertSame('book1', $result[0]->bookId);

        $this->assertInstanceOf(LoanRequestDto::class, $result[1]);
        $this->assertSame('user2', $result[1]->userId);
        $this->assertSame('book2', $result[1]->bookId);
    }

    public function test_it_should_handle_exception_when_listing_loan_requests(): void
    {
        $this->pdoMock->method('prepare')->willThrowException(new Exception('Error preparing SQL statement'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error preparing SQL statement');

        $this->sut->allLoanRequests();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdoMock = $this->createMock(PDO::class);
        $this->statementMock = $this->createMock(PDOStatement::class);
        $this->sut = new MySqlLoanRequestQueryService($this->pdoMock);
    }
}
