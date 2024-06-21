<?php
declare(strict_types=1);

namespace Action\Admin;

use App\Action\Admin\ReturnRequestsAction;
use App\Domain\Model\Book;
use App\Domain\ValueObject\Year;
use App\Service\ReturnRequestQueryServiceInterface;
use DateTime;
use PHPUnit\Framework\TestCase;

class ListReturnRequestsActionTest extends TestCase
{
    private ReturnRequestsAction $sut;
    private ReturnRequestQueryServiceInterface $returnRequestQueryService;

    public function test_it_should_list_all_return_requests(): void
    {
        $borrowDate1 = new DateTime('2023-01-01');
        $returnDate1 = new DateTime('2023-01-10');

        $borrowDate2 = new DateTime('2023-02-01');
        $returnDate2 = new DateTime('2023-02-12');

        $book1 = new Book('Test Title', 'Test Author', 'Català', new Year(2021), 'book1');
        $loan1 = $book1->borrow('user1', $borrowDate1);
        $loan1->markAsReturned($returnDate1);

        $book2 = new Book('Test Title', 'Test Author', 'Català', new Year(2021), 'book2');
        $loan2 = $book2->borrow('user2', $borrowDate2);
        $loan2->markAsReturned($returnDate2);

        $this->returnRequestQueryService
            ->expects($this->once())
            ->method('returnRequests')
            ->willReturn([$book1->returnedLoans()[0], $book2->returnedLoans()[0]]);

        $result = ($this->sut)();

        $this->assertCount(2, $result);
        $this->assertSame($loan1, $result[0]);
        $this->assertSame($loan2, $result[1]);
    }

    public function test_it_should_return_empty_array_if_no_return_requests(): void
    {
        $this->returnRequestQueryService
            ->expects($this->once())
            ->method('returnRequests')
            ->willReturn([]);

        $result = ($this->sut)();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->returnRequestQueryService = $this->createMock(ReturnRequestQueryServiceInterface::class);
        $this->sut = new ReturnRequestsAction($this->returnRequestQueryService);
    }
}
