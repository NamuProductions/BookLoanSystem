<?php
declare(strict_types=1);

namespace Service;

use App\Service\DatabaseService;
use App\Service\MySqlLoanRequestQueryService;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Service\LoanRequestDto;

class MySqlLoanRequestQueryServiceTest extends TestCase
{
    private PDO $pdo;
    private MySqlLoanRequestQueryService $sut;

    public function test_it_should_list_all_loan_requests(): void
    {
        $result = $this->sut->allLoanRequests();

        $this->assertCount(2, $result);

        $this->assertInstanceOf(LoanRequestDto::class, $result[0]);
        $this->assertSame('user1', $result[0]->userName);
        $this->assertSame('1', $result[0]->bookId);

        $this->assertInstanceOf(LoanRequestDto::class, $result[1]);
        $this->assertSame('user2', $result[1]->userName);
        $this->assertSame('2', $result[1]->bookId);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdo =(new DatabaseService())->getDatabaseConnection();
        $this->sut = new MySqlLoanRequestQueryService($this->pdo);
        $this->resetDatabase();
    }

    private function resetDatabase(): void
    {
        $this->pdo->exec("DELETE FROM loan_requests");
        $this->pdo->exec("DELETE FROM books");
        $this->pdo->exec("DELETE FROM users");

        $this->pdo->exec("INSERT INTO users (id, username, password, email, full_name) VALUES 
                          (1, 'user1', 'password1', 'user1@example.com', 'User One'), 
                          (2, 'user2', 'password2', 'user2@example.com', 'User Two')");
        $this->pdo->exec("INSERT INTO books (id, title, author, isbn, published_date, genre, pages, language) VALUES 
                          (1, 'Test Title 1', 'Author 1', '1234567890123', '2022-01-01', 'Fiction', 300, 'English'), 
                          (2, 'Test Title 2', 'Author 2', '1234567890124', '2022-02-01', 'Non-Fiction', 250, 'Català')");
        $this->pdo->exec("INSERT INTO loan_requests (id, book_id, user_id, borrowed_at, return_date) VALUES 
                          (1, 1, 1, '2023-05-01 00:00:00', NULL), 
                          (2, 2, 2, '2023-05-01 00:00:00', NULL)");
    }
}
