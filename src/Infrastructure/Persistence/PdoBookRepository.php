<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use App\Util\UUID;
use DateTime;
use PDO;

class PdoBookRepository implements BookRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Book $book): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO books (book_id, title, author, language, year, is_available)
            VALUES (:book_id, :title, :author, :language, :year, :is_available)
            ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                author = VALUES(author),
                language = VALUES(language),
                year = VALUES(year),
                is_available = VALUES(is_available)
        ');
        $stmt->execute([
            'book_id' => $book->bookId(),
            'title' => $book->title(),
            'author' => $book->author(),
            'language' => $book->language(),
            'year' => $book->year()->value(),
            'is_available' => $book->isAvailable() ? 1 : 0,
        ]);
    }

    public function findById(string $bookId): ?Book
    {
        $stmt = $this->pdo->prepare('SELECT * FROM books WHERE book_id = :book_id');
        $stmt->execute(['book_id' => $bookId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return $this->mapRowToBook($result);
    }

    public function findAvailableBooks(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM books WHERE is_available = 1');
        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = $this->mapRowToBook($row);
        }
        return $books;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM books');
        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = $this->mapRowToBook($row);
        }
        return $books;
    }

    public function search(string $query): array
    {
        $query = '%' . $query . '%';
        $stmt = $this->pdo->prepare('SELECT * FROM books WHERE title LIKE :query OR author LIKE :query');
        $stmt->execute(['query' => $query]);
        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = $this->mapRowToBook($row);
        }
        return $books;
    }

    public function findAllLoansByUser(string $userId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT b.*
            FROM books b
            INNER JOIN loans l ON b.book_id = l.book_id
            WHERE l.user_id = :userId AND l.returned_at IS NULL
        ');
        $stmt->execute(['userId' => $userId]);
        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = $this->mapRowToBook($row);
        }
        return $books;
    }
    public function borrowBook(string $bookId, string $userId): void
    {
        $stmt = $this->pdo->prepare('UPDATE books SET is_available = 0 WHERE book_id = :book_id');
        $stmt->execute(['book_id' => $bookId]);

        $loanId = UUID::generate();

        $stmt = $this->pdo->prepare('INSERT INTO loans (loan_id, book_id, user_id, borrowed_at) VALUES (:loan_id, :book_id, :user_id, :borrowed_at)');
        $stmt->execute([
            'loan_id' => $loanId,
            'book_id' => $bookId,
            'user_id' => $userId,
            'borrowed_at' => (new DateTime())->format('Y-m-d H:i:s')
        ]);
    }

    public function returnBook(string $bookId, string $userId): void
    {
        $stmt = $this->pdo->prepare('UPDATE books SET is_available = 1 WHERE book_id = :book_id');
        $stmt->execute(['book_id' => $bookId]);

        $stmt = $this->pdo->prepare('UPDATE loans SET returned_at = :returned_at WHERE book_id = :book_id AND user_id = :user_id AND returned_at IS NULL');
        $stmt->execute([
            'book_id' => $bookId,
            'user_id' => $userId,
            'returned_at' => (new DateTime())->format('Y-m-d H:i:s')
        ]);
    }

    private function mapRowToBook(array $row): Book
    {
        return new Book(
            $row['title'],
            new Year((int)$row['year']),
            $row['author'],
            isset($row['pages']) ? (int)$row['pages'] : null,
            $row['genre'],
            $row['language'],
            (bool)$row['is_available'],
            $row['book_id'],
        );
    }
}
