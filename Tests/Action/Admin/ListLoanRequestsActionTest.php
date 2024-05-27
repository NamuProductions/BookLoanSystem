<?php

namespace Action\Admin;


use App\Domain\Repository\LoanRepository;
use PHPUnit\Framework\TestCase;
use App\Action\Admin\ListLoanRequestsAction;
use PHPUnit\Framework\MockObject\Exception;


class ListLoanRequestsActionTest extends TestCase
{
    Private LoanRepository $loanRepository;
    Private ListLoanRequestsAction $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->LoanRepository = $this->createMock(LoanRepository::class);
        $this->sut = new ListLoanRequestsAction($this->LoanRepository);
    }
}