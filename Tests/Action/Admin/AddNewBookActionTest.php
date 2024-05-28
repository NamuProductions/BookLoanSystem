<?php

namespace Action\Admin;

use App\Action\Admin\AddNewBookAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
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
        $year = '2000';
        $idNumber = '0123456789';

        $this->bookRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Book $book) use ($title, $author, $year, $idNumber) {
                return $book->getTitle() === $title &&
                    $book->getAuthor() === $author &&
                    $book->getYear() === $year &&
                    $book->getIdNumber() === $idNumber;
            }));

        $this->sut->__invoke($title, $author, $year, $idNumber);
    }

    public function test_it_should_throw_an_exception_when_one_parameter_is_missing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title, author, year and idNumber are required');

        $this->sut->__invoke('', 'Test Author', 2000, '0123456789');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sut = new AddNewBookAction($this->bookRepository);
    }
}