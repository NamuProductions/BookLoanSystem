<?php

namespace Action\Admin;

use App\Action\Admin\AddNewBookAction;
use App\Domain\Repository\BookRepository;
use PHPUnit\Exception;
use PHPUnit\Framework\TestCase;

class AddNewBookActionTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        try{
            $this->bookRepository = $this->createMock(BookRepository::class);
        } catch (Exception) {
        }
        $this->sut = new AddNewBookAction($this->bookRepository);
    }

}