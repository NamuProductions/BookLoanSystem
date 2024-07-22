<?php
declare(strict_types=1);

namespace Action\Admin;

use App\Action\Admin\ReturnRequestsAction;
use App\Domain\Model\Book;
use App\Domain\Model\User;
use App\Domain\ValueObject\Year;
use App\Service\ReturnRequestQueryServiceInterface;
use DateTime;
use PHPUnit\Framework\TestCase;

class ReturnRequestsActionTest extends TestCase
{
    private ReturnRequestsAction $sut;
    private ReturnRequestQueryServiceInterface $returnRequestQueryService;

    private string $fixedUserId1;
    private string $fixedUserId2;

    public function test_it_should_list_all_return_requests(): void
    {
        $user1 = new User(
            userName: 'user1',
            password: 'testPassword',
            email: 'user1@test.com',
            fullName: 'User One',
            age: 25,
            role: 'user',
            userId: $this->fixedUserId1
        );
        $borrowDate1 = new DateTime('2023-01-01');

        $user2 = new User(
            userName: 'user2',
            password: 'testPassword',
            email: 'user2@test.com',
            fullName: 'User Two',
            age: 25,
            role: 'user',
            userId: $this->fixedUserId2
        );
        $borrowDate2 = new DateTime('2023-02-01');

        $book1 = new Book('Test Title', new Year(2021), 'Test Author', 1234, 'Testing','Català', true);
        $book1->borrow($user1, $borrowDate1);
        $book1->return($user1->userId());

        $book2 = new Book('Test Title 2', new Year(2021), 'Test Author', 1234, 'Testing','Català', true);
        $book2->borrow($user2, $borrowDate2);
        $book2->return($user2->userId());

        $loan1 = $book1->findAllLoansByUser($user1->userId())[0];
        $loan2 = $book2->findAllLoansByUser($user2->userId())[0];

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

        $this->fixedUserId1 = '11111111-1111-1111-1111-111111111111';
        $this->fixedUserId2 = '22222222-2222-2222-2222-222222222222';

        $this->returnRequestQueryService = $this->createMock(ReturnRequestQueryServiceInterface::class);
        $this->sut = new ReturnRequestsAction($this->returnRequestQueryService);
    }
}
