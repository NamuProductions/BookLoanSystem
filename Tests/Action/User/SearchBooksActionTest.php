<?php
declare(strict_types=1);

namespace Action\User;

use App\Action\User\SearchBooksAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use PHPUnit\Framework\TestCase;

class SearchBooksActionTest extends TestCase
{
    private BookRepository $bookRepository;
    private SearchBooksAction $sut;

    public function test_it_should_search_books_by_query(): void
    {
        $query = 'Title1';
        $matchedBooks = [
            new Book('Title1', 'Author1', 'Català', new Year(2023), 'ID123'),
        ];

        $this->bookRepository
            ->expects($this->once())
            ->method('search')
            ->with($query)
            ->willReturn($matchedBooks);

        $result = $this->sut->__invoke($query);

        $this->assertSame($matchedBooks, $result);
    }

    public function test_it_should_return_empty_list_for_no_matches(): void
    {
        $query = 'NonExistentTitle';

        $this->bookRepository->expects($this->once())
            ->method('search')
            ->with($query)
            ->willReturn([]);

        $result = $this->sut->__invoke($query);

        $this->assertEmpty($result);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sut = new SearchBooksAction($this->bookRepository);
    }
}
