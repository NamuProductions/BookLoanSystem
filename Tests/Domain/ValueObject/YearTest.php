<?php

namespace Domain\ValueObject;

use App\Domain\ValueObject\Year;
use PHPUnit\Framework\TestCase;

class YearTest extends TestCase
{
    public function test_it_should_create_valid_year_in_Anno_Domini()
    {
        $year = new Year(2023);
        $this->assertInstanceOf(Year::class, $year);
        $this->assertEquals(2023, $year->getValue());
        $this->assertEquals("2023", (string)$year);
    }
}
