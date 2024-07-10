<?php
declare(strict_types=1);

namespace Action\Admin;

use App\Action\Admin\AddNewBookAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use App\Util\UUID;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use DateTime;

class AddNewBookActionTest extends TestCase
{
    private BookRepository $bookRepository;
    private AddNewBookAction $sut;

    public function test_it_should_add_a_new_book(): void
    {
       $bookIdGenerated = UUID::generate();

        $title = 'Test Title';
        $author = 'Test Author';
        $language = 'English';
        $year = new Year(2024);
        $pages = 123;
        $genre = 'Test Genre';
        $bookId = $bookIdGenerated;
        $createdAt = new DateTime('2024-07-09 10:00:00');
        $isAvailable = true;

        $this->bookRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Book $book) use ($title, $author, $language, $year, $pages, $genre, $bookId, $createdAt, $isAvailable) {
                return $book->title() === $title &&
                    $book->author() === $author &&
                    $book->language() === $language &&
                    $book->year()->value() === $year->value() &&
                    $book->pages() === $pages &&
                    $book->genre() === $genre &&
                    $book->bookId() === $bookId &&
                    $book->createdAt()->format('Y-m-d H:i:s') === $createdAt->format('Y-m-d H:i:s') &&
                    $book->isAvailable() === $isAvailable;
            }));

        $this->sut->__invoke($title, $author, $language, $year, $pages, $genre, $bookId, $createdAt);
    }

    public function test_it_should_throw_exception_if_title_is_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title is required');

        $this->sut->__invoke('', 'Test Author', 'English', new Year(2024));
    }

    public function test_it_should_throw_exception_if_author_is_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Author is required');

        $this->sut->__invoke('Test Title', '', 'English', new Year(2024));
    }

    public function test_it_should_throw_exception_if_language_is_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Language is required');

        $this->sut->__invoke('Test Title', 'Test Author', '', new Year(2024));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sut = new AddNewBookAction($this->bookRepository);
    }
}
