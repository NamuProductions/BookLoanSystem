<?php

namespace Action\User;

use App\Action\User\ListAvailableBooksAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use PHPUnit\Framework\TestCase;

class ListAvailableBooksActionTest extends TestCase
{
    private BookRepository $bookRepository;
    private ListAvailableBooksAction $sut;

    public function test_it_should_list_all_available_books(): void
    {
        $availableBooks = [
            new Book('Book 1', 'Author 1', '2012', '012345'),
            new Book('Book 2', 'Author 2', '1989', '123456')
        ];

        $this->bookRepository
            ->expects($this->once())
            ->method('findAvailableBooks')
            ->willReturn($availableBooks);

        $result = $this->sut->__invoke();

        $this->assertSame($availableBooks, $result);
    }

    public function test_it_should_return_empty_list_if_no_books_available(): void
    {
        $this->bookRepository
            ->expects($this->once())
            ->method('findAvailableBooks')
            ->willReturn([]);

        $result = $this->sut->__invoke();

        $this->assertSame([], $result);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sut = new ListAvailableBooksAction($this->bookRepository);
    }
}
