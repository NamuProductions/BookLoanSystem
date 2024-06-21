<?php
declare(strict_types=1);

namespace Action\Admin;

use App\Action\Admin\AddNewBookAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use PHPUnit\Event\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AddNewBookActionTest extends TestCase
{

    private BookRepository $bookRepository;
    private AddNewBookAction $sut;

    public function test_it_should_add_a_new_book(): void
    {
        $title = 'Test Book';
        $author = 'Test Author';
        $language = 'Català';
        $year = new Year(2000);
        $idNumber = '0123456789';

        $this->bookRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Book $book) use ($title, $author, $language, $year, $idNumber) {
                return $book->title() === $title &&
                    $book->author() === $author &&
                    $book->language() === $language &&
                    $book->year() === $year &&
                    $book->bookId() === $idNumber;
            }));

        $this->sut->__invoke($title, $author, $language, $year, $idNumber);
    }

    public function test_it_should_throw_an_exception_when_title_is_missing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title is required');

        $this->sut->__invoke('', 'Test Author', 'Català', new Year(2000), '0123456789');
    }

    public function test_it_should_throw_an_exception_when_author_is_missing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Author is required');

        $this->sut->__invoke('Title1', '', 'Català', new Year(2000), '0123456789');
    }

    public function test_it_should_throw_an_exception_when_idNumber_is_missing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('IdNumber is required');

        $this->sut->__invoke('Title1', 'Test Author', 'Català', new Year(2000), '');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sut = new AddNewBookAction($this->bookRepository);
    }
}