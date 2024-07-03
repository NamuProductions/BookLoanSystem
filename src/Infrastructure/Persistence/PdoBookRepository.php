<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
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
            INSERT INTO books (id, title, author, language, year, is_available)
            VALUES (:id, :title, :author, :language, :year, :is_available)
            ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                author = VALUES(author),
                language = VALUES(language),
                year = VALUES(year),
                is_available = VALUES(is_available)
        ');
        $stmt->execute([
            'id' => $book->bookId(),
            'title' => $book->title(),
            'author' => $book->author(),
            'language' => $book->language(),
            'year' => $book->year()->getValue(),
            'is_available' => $book->isAvailable() ? 1 : 0,
        ]);
    }

    public function findById(string $id): ?Book
    {
        $stmt = $this->pdo->prepare('SELECT * FROM books WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return new Book(
            $result['title'],
            $result['author'],
            $result['language'],
            new Year((int)$result['year']),
            $result['id'],
            (bool)$result['is_available']
        );
    }

    public function findAvailableBooks(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM books WHERE is_available = 1');
        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Book(
                $row['title'],
                $row['author'],
                $row['language'],
                new Year((int)$row['year']),
                $row['id'],
                (bool)$row['is_available']
            );
        }
        return $books;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM books');
        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Book(
                $row['title'],
                $row['author'],
                $row['language'],
                new Year((int)$row['year']),
                $row['id'],
                (bool)$row['is_available']
            );
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
            $books[] = new Book(
                $row['title'],
                $row['author'],
                $row['language'],
                new Year((int)$row['year']),
                $row['id'],
                (bool)$row['is_available']
            );
        }
        return $books;
    }

    public function findAllLoansByUser(string $userId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT b.*
            FROM books b
            INNER JOIN loan_requests l ON b.id = l.book_id
            WHERE l.user_id = :userId AND l.return_date IS NULL
        ');
        $stmt->execute(['userId' => $userId]);
        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Book(
                $row['title'],
                $row['author'],
                $row['language'],
                new Year((int)$row['year']),
                $row['id'],
                (bool)$row['is_available']
            );
        }
        return $books;
    }
}
