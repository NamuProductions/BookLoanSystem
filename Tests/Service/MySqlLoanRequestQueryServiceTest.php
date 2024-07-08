<?php
declare(strict_types=1);

namespace Service;

use App\Service\DatabaseService;
use App\Service\MySqlLoanRequestQueryService;
use App\Util\UUID;
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
        $this->pdo->exec("DELETE FROM loans");
        $this->pdo->exec("DELETE FROM books");
        $this->pdo->exec("DELETE FROM users");

        $userId1 = UUID::generate();
        $this->pdo->exec("INSERT INTO users (user_id, user_name, password, email, full_name) 
                      VALUES ('$userId1', 'user1', 'password1', 'user1@example.com', 'User One')");

        $userId2 = UUID::generate();
        $this->pdo->exec("INSERT INTO users (user_id, user_name, password, email, full_name) 
                      VALUES ('$userId2', 'user2', 'password2', 'user2@example.com', 'User Two')");

        $bookId1 = UUID::generate();
        $this->pdo->exec("INSERT INTO books (book_id, title, author, year, pages, genre, language, created_at, is_available) VALUES 
                      ('$bookId1', 'Test Title 1', 'Author 1', 1989, 1234, 'Fiction', 'Català', '2023-08-08', true)");

        $bookId2 = UUID::generate();
        $this->pdo->exec("INSERT INTO books (book_id, title, author, year, pages, genre, language, created_at, is_available) VALUES 
                      ('$bookId2', 'Test Title 2', 'Author 2', 1989, 1234, 'Fiction', 'Català', '2023-08-08', true)");

        $loanId1 = UUID::generate();
        $this->pdo->exec("INSERT INTO loans (loan_id, book_id, user_id, borrowed_at, status) VALUES 
                      ('$loanId1', '$bookId1', '$userId1', '2023-05-01 00:00:00', 'pending')");

        $loanId2 = UUID::generate();
        $this->pdo->exec("INSERT INTO loans (loan_id, book_id, user_id, borrowed_at, status) VALUES 
                      ('$loanId2', '$bookId2', '$userId2', '2023-05-01 00:00:00', 'pending')");
    }

}
