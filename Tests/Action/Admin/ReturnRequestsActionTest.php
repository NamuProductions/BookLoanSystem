<?php

declare(strict_types=1);

namespace Action\Admin;

use App\Action\Admin\ReturnRequestsAction;
use App\Domain\Model\Book;
use App\Domain\Model\User;
use App\Domain\ValueObject\Age;
use App\Domain\ValueObject\UserName;
use App\Domain\ValueObject\Year;
use App\Service\ReturnRequestQueryServiceInterface;
use DateTime;
use PHPUnit\Framework\TestCase;

class ReturnRequestsActionTest extends TestCase
{
    private ReturnRequestsAction $sut;
    private ReturnRequestQueryServiceInterface $returnRequestQueryService;

    private User $user1;
    private User $user2;

    public function test_it_should_list_all_return_requests(): void
    {
        $borrowDate1 = new DateTime('2023-01-01');
        $borrowDate2 = new DateTime('2023-02-01');

        $book1 = new Book('Test Title', new Year(2021), 'Test Author', 1234, 'Testing', 'Català', true);
        $book1->borrow($this->user1, $borrowDate1);
        $book1->return($this->user1->userId());

        $book2 = new Book('Test Title 2', new Year(2021), 'Test Author', 1234, 'Testing', 'Català', true);
        $book2->borrow($this->user2, $borrowDate2);
        $book2->return($this->user2->userId());

        $loan1 = $book1->findAllLoansByUser($this->user1->userId())[0];
        $loan2 = $book2->findAllLoansByUser($this->user2->userId())[0];

        $this->returnRequestQueryService
            ->expects($this->once())
            ->method('returnRequests')
            ->willReturn([$loan1, $loan2]);

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

        $this->user1 = new User(
            new UserName('user1'),
            'testPassword',
            'user1@test.com',
            'User One',
            new Age(25),
            'user',
            '11111111-1111-1111-1111-111111111111'
        );

        $this->user2 = new User(
            new UserName('user2'),
            'testPassword',
            'user2@test.com',
            'User Two',
            new Age(25),
            'user',
            '22222222-2222-2222-2222-222222222222'
        );

        $this->returnRequestQueryService = $this->createMock(ReturnRequestQueryServiceInterface::class);
        $this->sut = new ReturnRequestsAction($this->returnRequestQueryService);
    }
}
