<?php

namespace Action\User;

use App\Action\User\SearchBooksAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use PHPUnit\Framework\TestCase;

class SearchBooksActionTest extends TestCase
{
    private BookRepository $bookRepository;
    private SearchBooksAction $sut;

    public function test_it_should_search_books_by_query(): void
    {
        $query = 'Title1';
        $matchedBooks = [
            new Book('Title1', 'Author1', '2023', 'ID123'),
        ];

        $this->bookRepository
            ->expects($this->once())
            ->method('search')
            ->with($query)
            ->willReturn($matchedBooks);

        $result = $this->sut->__invoke($query);

        $this->assertSame($matchedBooks, $result);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sut = new SearchBooksAction($this->bookRepository);
    }
}
