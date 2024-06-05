<?php
declare(strict_types=1);

namespace Action\Admin;

use App\Action\Admin\ListReturnRequestsAction;
use App\Domain\Model\Book;
use App\Domain\Repository\BookRepository;
use App\Domain\ValueObject\Year;
use DateTime;
use PHPUnit\Framework\TestCase;

class ListReturnRequestsActionTest extends TestCase
{
    private ListReturnRequestsAction $sut;
    private BookRepository $bookRepository;

    public function test_it_should_list_all_return_requests(): void
    {
        $borrowDate1 = new DateTime('2023-01-01');
        $returnDate1 = new DateTime('2023-01-10');

        $borrowDate2 = new DateTime('2023-02-01');
        $returnDate2 = new DateTime('2023-02-12');

        $book1 = new Book('Test Title', 'Test Author', new Year(2021), 'book1');
        $loan1 = $book1->borrow('user1', $borrowDate1);
        $loan1->markAsReturned($returnDate1);

        $book2 = new Book('Test Title', 'Test Author', new Year(2021), 'book2');
        $loan2 = $book2->borrow('user2', $borrowDate2);
        $loan2->markAsReturned($returnDate2);

        $this->bookRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$book1, $book2]);

        $result = ($this->sut)();

        $this->assertCount(2, $result);
        $this->assertSame($loan1, $result[0]);
        $this->assertSame($loan2, $result[1]);
    }

    public function test_it_should_return_empty_array_if_no_return_requests(): void
    {
        $this->bookRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $result = ($this->sut)();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->sut = new ListReturnRequestsAction($this->bookRepository);
    }
}
